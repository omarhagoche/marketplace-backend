<?php
/**
 * File name: ExtraController.php
 * Last modified: 2020.04.30 at 08:21:09
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\Operations;

use App\Criteria\Extras\ExtrasOfUserCriteria;
use App\Criteria\Foods\FoodsOfUserCriteria;
use App\DataTables\ExtraDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExtraRequest;
use App\Http\Requests\UpdateExtraRequest;
use App\Models\Restaurant;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ExtraGroupRepository;
use App\Repositories\ExtraRepository;
use App\Repositories\FoodRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ExtraController extends Controller
{
    /** @var  ExtraRepository */
    private $extraRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var FoodRepository
     */
    private $foodRepository;
    /**
     * @var ExtraGroupRepository
     */
    private $extraGroupRepository;
    /**
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    public function __construct(RestaurantRepository $restaurantRepository, ExtraRepository $extraRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo
        , FoodRepository $foodRepo
        , ExtraGroupRepository $extraGroupRepo)
    {
        parent::__construct();
        $this->extraRepository = $extraRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->foodRepository = $foodRepo;
        $this->extraGroupRepository = $extraGroupRepo;
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * Display a listing of the Extra.
     *
     * @param ExtraDataTable $extraDataTable
     * @return Response
     */
    public function index(ExtraDataTable $extraDataTable)
    {
        return $extraDataTable->render('operations.restaurantProfile.extras.index');
    }

    public function indexByRestaurant(ExtraDataTable $extraDataTable, $id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        return $extraDataTable->with(['id'=>$id])->render('operations.restaurantProfile.extras.index',compact('id','restaurant'));
    }

    /**
     * Show the form for creating a new Extra.
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function create()
    {
        //$this->foodRepository->pushCriteria(new FoodsOfUserCriteria(auth()->id()));
        //$food = $this->foodRepository->groupedByRestaurants();
        $restaurant = Restaurant::pluck('name', 'id');
        $extraGroup = $this->extraGroupRepository->pluck('name', 'id');

        $hasCustomField = in_array($this->extraRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
            $html = generateCustomField($customFields);
        }
        
        return view('operations.restaurantProfile.extras.create')->with("customFields", isset($html) ? $html : false)->with("restaurant", $restaurant)->with("extraGroup", $extraGroup);
    }

    /**
     * Show the form for creating a new Extra By Restaurent id.
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function createByrestuarant($id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        $extraGroup = $this->extraGroupRepository->pluck('name', 'id');
        
        return view('operations.restaurantProfile.extras.create')->with("restaurant", $restaurant)->with("extraGroup", $extraGroup)->with('id',$id);
    }

    /**
     * Store a newly created Extra in storage.
     *
     * @param CreateExtraRequest $request
     *
     * @return Response
     */
    public function store(CreateExtraRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
        try {
            $extra = $this->extraRepository->create($input);
            $extra->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($extra, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.extra')]));

        return redirect(route('operations.restaurant.extra.create',$request->restaurant_id));
    }

    /**
     * Display the specified Extra.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show($id)
    {
        $this->extraRepository->pushCriteria(new ExtrasOfUserCriteria(auth()->id()));

        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        return view('extras.show')->with('extra', $extra);
    }

    
    public function editByRestuarant($id,$restaurant_id)
    {
        $extra = $this->extraRepository->findWithoutFail($id);
        if (empty($extra)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.extra')]));
            return redirect(route('extras.index'));
        }
        $extraGroup = $this->extraGroupRepository->pluck('name', 'id');
        
        $restaurant = $this->restaurantRepository->findWithoutFail($restaurant_id);

        return view('operations.restaurantProfile.extras.edit')->with("id", $restaurant->id)->with('extra', $extra)->with("restaurant", $restaurant)->with("extraGroup", $extraGroup);
    }

    /**
     * Show the form for editing the specified Extra.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */

    public function edit($id)
    {
        $this->extraRepository->pushCriteria(new ExtrasOfUserCriteria(auth()->id()));
        $extra = $this->extraRepository->findWithoutFail($id);
        if (empty($extra)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.extra')]));
            return redirect(route('extras.index'));
        }
        #$this->foodRepository->pushCriteria(new FoodsOfUserCriteria(auth()->id()));
        #$food = $this->foodRepository->groupedByRestaurants();
        $restaurant = Restaurant::pluck('name', 'id');
        $extraGroup = $this->extraGroupRepository->pluck('name', 'id');


        $customFieldsValues = $extra->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
        $hasCustomField = in_array($this->extraRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('extras.edit')->with('extra', $extra)->with("customFields", isset($html) ? $html : false)->with("restaurant", $restaurant)->with("extraGroup", $extraGroup);
    }

    /**
     * Update the specified Extra in storage.
     *
     * @param int $id
     * @param UpdateExtraRequest $request
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update($id, UpdateExtraRequest $request)
    {
        $this->extraRepository->pushCriteria(new extrasOfUserCriteria(auth()->id()));

        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');
            return redirect(route('extras.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
        try {
            $extra = $this->extraRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($extra, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $extra->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.extra')]));

        return redirect(route('operations.restaurant.extra.index',$request->restaurant_id));
    }

    /**
     * Remove the specified Extra from storage.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy($id)
    {
        $this->extraRepository->pushCriteria(new extrasOfUserCriteria(auth()->id()));
        $extra = $this->extraRepository->findWithoutFail($id);
        
        if($extra->foods->count() != 0) {
            Flash::error('Extra Related To Food');

            return redirect(route('operations.restaurant.extra.index',$extra->restaurant_id));
        }
        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        $this->extraRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.extra')]));

        return redirect(route('operations.restaurant.extra.index',$extra->restaurant_id));
    }

    /**
     * Remove Media of extra
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $extra = $this->extraRepository->findWithoutFail($input['id']);
        try {
            if ($extra->hasMedia($input['collection'])) {
                $extra->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
