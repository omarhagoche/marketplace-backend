<?php

namespace App\Http\Controllers\Operations;

use Flash;
use App\Models\Order;
use App\Http\Requests;
use App\Models\Driver;
use App\Models\DriverType;
use Illuminate\Http\Request;
use App\DataTables\Operations\DriverSearchDataTable;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\OrderRepository;
use App\Repositories\DriverRepository;
use App\Repositories\DriverReviewRepository;
use App\Criteria\Users\DriversCriteria;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\CreateDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Repositories\DriverTypeRepository;
use App\Repositories\CustomFieldRepository;
use Prettus\Validator\Exceptions\ValidatorException;

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
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var DriverReviewRepository
     */
    private $reviewRepository;

    public function __construct(
        DriverRepository $driverRepo,
        DriverTypeRepository $driverTypeRepo,
        CustomFieldRepository $customFieldRepo,
        UserRepository $userRepo,
        OrderRepository $orderRepository,
        DriverReviewRepository $reviewRepository
    ) {
        parent::__construct();
        $this->driverRepository = $driverRepo;
        $this->driverTypeRepository = $driverTypeRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->userRepository = $userRepo;
        $this->orderRepository = $orderRepository;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Display a listing of the Driver.
     *
     * @param DriverSearchDataTable $driverDataTable
     * @return Response
     */
    public function index(DriverSearchDataTable $driverDataTable)
    {
        return $driverDataTable->render('operations.drivers.index');
    }


    /**
     * Display the specified Driver.
     *
     * @return Response
     */
    public function map()
    {
        return view('operations.drivers.map');
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
        return view('operations.drivers.create')->with('types', $types)->with('customFields', 0);
        // return redirect(route('drivers.create'))->with('types', $types);
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
        $input['password'] = Hash::make($input['password']);
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->driverRepository->model());
        try {
            $user = $this->userRepository->create($input);
            $input['user_id'] = $user->id;
            $driver = $this->driverRepository->create($input);
            $driver->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        $input = $request->all();
        $user = User::create([
            'name' => $input['name'],
            'phone_number' => $input['phone_number'],
            'password' => Hash::make($input['password']),
            'email' => $input['email'],
            'active' => $input['active'],
        ]);
        Driver::create([
            'user_id' => $user->id,
            'driver_type_id' => $input['driver_type_id'],
            'available' => $input['available'],
            'delivery_fee' => $input['delivery_fee'],
        ]);

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.driver')]));

        return redirect(route('operations.drivers.index'));
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
        if ($driver) {
            $user = $this->userRepository->findWithoutFail($driver->user_id);
            $reviews = $this->reviewRepository->with('user')->where('driver_id', $driver->id)->paginate(5);
        }
        if (empty($driver) || empty($user)) {
            Flash::error('Driver not found');
            return redirect(route('operations.drivers.index'));
        }

        $ordersOfDay =  $driver->getOrdersBetweenDaysCount(1);
        $ordersOfWeek = $driver->getOrdersBetweenDaysCount(7);
        $ordersOfMonth = $driver->getOrdersBetweenDaysCount(30);

        $orders = $this->orderRepository->where('driver_id', $driver->id)->with('user')->with('restaurant')->orderby('created_at', 'desc')->paginate(5);
        $lastOrder = $orders->first();
        return view('operations.drivers.show')->with('driver', $driver)
            ->with('user', $user)
            ->with('reviews', $reviews)
            ->with('orders', $orders)
            ->with('lastOrder', $lastOrder)
            ->with('ordersOfDay', $ordersOfDay)
            ->with('ordersOfWeek', $ordersOfWeek)
            ->with('ordersOfMonth', $ordersOfMonth);
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
        if ($driver)
            $user = $this->userRepository->findWithoutFail($driver->user_id);
        if (empty($driver)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.driver')]));

            return redirect(route('operations.drivers.index'));
        }

        $customFieldsValues = $driver->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->driverRepository->model());
        $hasCustomField = in_array($this->driverRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        $types = $this->driverTypeRepository->pluck('name', 'id');
        return view('operations.drivers.edit')->with('driver', $driver)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with('types', $types);
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
        if ($driver) {
            $user = $this->userRepository->findWithoutFail($driver->user_id);
        }

        if (empty($driver) || empty($user)) {
            Flash::error('Driver not found');
            return redirect(route('operations.drivers.index'));
        }

        $input = $request->all();
        if ($request->password != null) {
            if ($input['password'] && ($input['password'] == $input['password_confirmation'])) {
                $request->validated(['password' => 'confirmed']);
                $input['password'] = Hash::make($input['password']);
            }
        } else {
            unset($input['password']);
            unset($input['password_confirmation']);
        }
        
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->driverRepository->model());
        try {
            $driver = $this->driverRepository->update($input, $id);
            $user = $this->userRepository->update($input, $driver->user_id);

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $driver->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.driver')]));

        return redirect(route('operations.drivers.index'));
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

        return redirect(route('operations.drivers.index'));
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

        if (empty($driver) || $driver->orders->count() > 0) {
            Flash::error('Driver not found or driver have orders' );

            return redirect(route('operations.drivers.index'));
        }
        $driver->delete();
        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.driver')]));

        return redirect(route('operations.drivers.index'));
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
