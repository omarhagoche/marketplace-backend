<?php

namespace App\Http\Controllers;

use App\DataTables\DayDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDayRequest;
use App\Http\Requests\UpdateDayRequest;
use App\Repositories\DayRepository;
use App\Repositories\CustomFieldRepository;

use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class DayController extends Controller
{
    /** @var  DayRepository */
    private $dayRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    

    public function __construct(DayRepository $dayRepo, CustomFieldRepository $customFieldRepo )
    {
        parent::__construct();
        $this->dayRepository = $dayRepo;
        $this->customFieldRepository = $customFieldRepo;
        
    }

    /**
     * Display a listing of the Day.
     *
     * @param DayDataTable $dayDataTable
     * @return Response
     */
    public function index(DayDataTable $dayDataTable)
    {
        return $dayDataTable->render('days.index');
    }

    /**
     * Show the form for creating a new Day.
     *
     * @return Response
     */
    public function create()
    {
        
        
        $hasCustomField = in_array($this->dayRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->dayRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('days.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Day in storage.
     *
     * @param CreateDayRequest $request
     *
     * @return Response
     */
    public function store(CreateDayRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->dayRepository->model());
        try {
            $day = $this->dayRepository->create($input);
            $day->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.day')]));

        return redirect(route('days.index'));
    }

    /**
     * Display the specified Day.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $day = $this->dayRepository->findWithoutFail($id);

        if (empty($day)) {
            Flash::error('Day not found');

            return redirect(route('days.index'));
        }

        return view('days.show')->with('day', $day);
    }

    /**
     * Show the form for editing the specified Day.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $day = $this->dayRepository->findWithoutFail($id);
        
        

        if (empty($day)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.day')]));

            return redirect(route('days.index'));
        }
        $customFieldsValues = $day->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->dayRepository->model());
        $hasCustomField = in_array($this->dayRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('days.edit')->with('day', $day)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Day in storage.
     *
     * @param  int              $id
     * @param UpdateDayRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDayRequest $request)
    {
        $day = $this->dayRepository->findWithoutFail($id);

        if (empty($day)) {
            Flash::error('Day not found');
            return redirect(route('days.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->dayRepository->model());
        try {
            $day = $this->dayRepository->update($input, $id);
            
            
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $day->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.day')]));

        return redirect(route('days.index'));
    }

    /**
     * Remove the specified Day from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $day = $this->dayRepository->findWithoutFail($id);

        if (empty($day)) {
            Flash::error('Day not found');

            return redirect(route('days.index'));
        }

        $this->dayRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.day')]));

        return redirect(route('days.index'));
    }

        /**
     * Remove Media of Day
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $day = $this->dayRepository->findWithoutFail($input['id']);
        try {
            if($day->hasMedia($input['collection'])){
                $day->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
