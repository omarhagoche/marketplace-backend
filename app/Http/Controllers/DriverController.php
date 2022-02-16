<?php

namespace App\Http\Controllers;

use App\Criteria\Users\DriversCriteria;
use App\DataTables\DriverDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use App\Repositories\DriverRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\DriverType;
use App\Repositories\DriverTypeRepository;


class DriverController extends Controller
{
    /** @var  DriverRepository */
    private $driverRepository;

    /** @var  DriverTypeRepository */
    private $driverTypeRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(DriverRepository $driverRepo, DriverTypeRepository $driverTypeRepo, CustomFieldRepository $customFieldRepo, UserRepository $userRepo)
    {
        parent::__construct();
        $this->driverRepository = $driverRepo;
        $this->driverTypeRepository = $driverTypeRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the Driver.
     *
     * @param DriverDataTable $driverDataTable
     * @return Response
     */
    public function index(DriverDataTable $driverDataTable)
    {
        return $driverDataTable->render('drivers.index');
    }


    /**
     * Display the specified Driver.
     *
     * @return Response
     */
    public function map()
    {
        return view('drivers.map');
    }

    /**
     * Show the form for creating a new Driver.
     *
     * @return Response
     */
    public function create()
    {
        $this->userRepository->pushCriteria(new DriversCriteria());
        $drivers = $this->userRepository->all();
        foreach ($drivers as $driver) {
            if (!empty($driver)) {
                $this->driverRepository->firstOrCreate(['user_id' => $driver->id]);
            }
        }

        $types = $this->driverTypeRepository->pluck('name', 'id');
        return redirect(route('drivers.index'))->with('types', $types);
    }

    /**
     * Store a newly created Driver in storage.
     *
     * @param CreateDriverRequest $request
     *
     * @return Response
     */
    public function store(CreateDriverRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->driverRepository->model());
        try {
            $driver = $this->driverRepository->create($input);
            $driver->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.driver')]));

        return redirect(route('drivers.index'));
    }

    /**
     * Display the specified Driver.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $driver = $this->driverRepository->findWithoutFail($id);
        $user = $this->userRepository->findWithoutFail($driver->user_id);

        if (empty($driver) || empty($user)) {
            Flash::error('Driver not found');

            return redirect(route('drivers.index'));
        }

        return view('drivers.show')->with('driver', $driver)->with('user', $user);
    }

    /**
     * Show the form for editing the specified Driver.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $driver = $this->driverRepository->findWithoutFail($id);
        $user = $this->userRepository->pluck('name', 'id');


        if (empty($driver)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.driver')]));

            return redirect(route('drivers.index'));
        }
        $customFieldsValues = $driver->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->driverRepository->model());
        $hasCustomField = in_array($this->driverRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        $types = $this->driverTypeRepository->pluck('name', 'id');
        return view('drivers.edit')->with('driver', $driver)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with('types', $types);
    }

    /**
     * Update the specified Driver in storage.
     *
     * @param  int              $id
     * @param UpdateDriverRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDriverRequest $request)
    {
        $driver = $this->driverRepository->findWithoutFail($id);

        if (empty($driver)) {
            Flash::error('Driver not found');
            return redirect(route('drivers.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->driverRepository->model());
        try {
            $driver = $this->driverRepository->update($input, $id);


            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $driver->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.driver')]));

        return redirect(route('drivers.index'));
    }


    /**
     * Update Data Drivers information in firestore.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function updateDataInFirestore(Request $request)
    {
        // bring drivers from firestore
        $db_drivers = app('firebase.firestore')->getFirestore()->collection('drivers')->documents();
        // save data in Associative Arrays to make it easy access to it, just by id
        $firestore_drivers  = [];
        foreach ($db_drivers as $doc) {
            $firestore_drivers[$doc->id()] = $doc->data();
        }
        // end bring data from firestore

        // bring drivers from database
        $drivers = Driver::with('user:id,name,phone_number')->orderBy('user_id')->get();

        // start batch upload data to firestore
        $db = app('firebase.firestore')->getFirestore();
        $batch = $db->batch();

        foreach ($drivers as $d) {
            $driver = $firestore_drivers[$d->user_id] ?? [];
            if (!$driver) {
                $driver = [
                    'id' => $d->user_id,
                    'latitude' => 0,
                    'longitude' => 0,
                    'last_access' => null,
                ];
            }

            $driver['name'] = $d->user->name;
            $driver['phone_number'] = $d->user->phone_number;
            $driver['driver_type_id'] = $d->driver_type_id;
            $driver['working_on_order'] = $d->working_on_order;
            $driver['available'] = $d->available;

            // set document in batch
            $docRef = $db->collection("drivers")->document($driver['id']);
            $batch->set($docRef, $driver);
        }

        $batch->commit(); // upload or commit batch
        // end batch upload data to firestore

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.driver')]));

        return redirect(route('drivers.index'));
    }

    /**
     * Remove the specified Driver from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $driver = $this->driverRepository->findWithoutFail($id);

        if (empty($driver)) {
            Flash::error('Driver not found');

            return redirect(route('drivers.index'));
        }

        $this->driverRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.driver')]));

        return redirect(route('drivers.index'));
    }

    /**
     * Remove Media of Driver
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $driver = $this->driverRepository->findWithoutFail($input['id']);
        try {
            if ($driver->hasMedia($input['collection'])) {
                $driver->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
