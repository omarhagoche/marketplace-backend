<?php
/**
 * File name: RestaurantAPIController.php
 * Last modified: 2020.08.20 at 16:23:39
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Http\Controllers\API\Manager;


use Flash;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\UploadRepository;
use App\Criteria\Restaurants\NearCriteria;
use App\Repositories\RestaurantRepository;
use App\Repositories\CustomFieldRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Criteria\Restaurants\RestaurantsOfManagerCriteria;

/**
 * Class RestaurantController
 * @package App\Http\Controllers\API
 */

class RestaurantAPIController extends Controller
{
    /** @var  RestaurantRepository */
    private $restaurantRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;


    public function __construct(RestaurantRepository $restaurantRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->restaurantRepository = $restaurantRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;

    }

    /**
     * Display a listing of the Restaurant.
     * GET|HEAD /restaurants
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->restaurantRepository->pushCriteria(new RequestCriteria($request));
            $this->restaurantRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->restaurantRepository->pushCriteria(new RestaurantsOfManagerCriteria(auth()->id()));
            $restaurants = $this->restaurantRepository->all();

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($restaurants->toArray(), 'Restaurants retrieved successfully');
    }

    /**
     * Display the specified Restaurant.
     * GET|HEAD /restaurants/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        /** @var Restaurant $restaurant */
        if (!empty($this->restaurantRepository)) {
            try{
                $this->restaurantRepository->pushCriteria(new RequestCriteria($request));
                $this->restaurantRepository->pushCriteria(new LimitOffsetCriteria($request));
                if ($request->has(['myLon', 'myLat', 'areaLon', 'areaLat'])) {
                    $this->restaurantRepository->pushCriteria(new NearCriteria($request));
                }
            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
            $restaurant = $this->restaurantRepository->findWithoutFail($id);
        }

        if (empty($restaurant)) {
            return $this->sendError('Restaurant not found');
        }

        return $this->sendResponse($restaurant->toArray(), 'Restaurant retrieved successfully');
    }

    /**
     * Store a newly created Restaurant in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if (auth()->user()->hasRole('manager')){
            $input['users'] = [auth()->id()];
        }
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        try {
            $restaurant = $this->restaurantRepository->create($input);
            $restaurant->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                upload_image($request->image, $restaurant->id, 'image')
                    ->getMedia('image')
                    ->first()
                    ->copy($restaurant, 'image');

                $restaurant->load('media'); // load media relationship to load images 
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($restaurant->toArray(),__('lang.saved_successfully', ['operator' => __('lang.restaurant')]));
    }

    /**
     * Update the specified Restaurant in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);

        if (empty($restaurant)) {
            return $this->sendError('Restaurant not found');
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        try {
            $restaurant = $this->restaurantRepository->update($input, $id);
            $input['users'] = isset($input['users']) ? $input['users'] : [];
            $input['drivers'] = isset($input['drivers']) ? $input['drivers'] : [];
            if (isset($input['image']) && $input['image']) {
                if ($restaurant->hasMedia('image')) {
                    $restaurant->getFirstMedia('image')->delete();
                }
                upload_image($request->image, $restaurant->id, 'image')
                    ->getMedia('image')
                    ->first()
                    ->copy($restaurant, 'image');

                $restaurant->load('media'); // load media relationship to load images of food
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $restaurant->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($restaurant->toArray(),__('lang.updated_successfully', ['operator' => __('lang.restaurant')]));
    }

    /**
     * Remove the specified Restaurant from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);

        if (empty($restaurant)) {
            return $this->sendError('Restaurant not found');
        }

        $restaurant = $this->restaurantRepository->delete($id);

        return $this->sendResponse($restaurant,__('lang.deleted_successfully', ['operator' => __('lang.restaurant')]));
    }

                            /* THIS ROUTE FOR ADD DAY TO RESTAURANT  */

    public function days($id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        if (empty($restaurant)) {
            return $this->sendError('Restaurants Not found !!');

        }
        return $this->sendResponse($restaurant->days->toArray(), 'Restaurants retrieved successfully');

    }
    public function daysStore(Request $request,$restaurantId)
    {
        $this->validate($request, [
            'day_id' => 'required',
            'close_at' => 'required',
            'open_at' => 'required',
        ]);

        try {
            DB::beginTransaction();

                $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
                if (empty($restaurant)) {
                    return $this->sendError('Restaurants Not found !!');
        
                }
                $restaurant->days()->detach($request->day_id); 
                $restaurant->days()->attach($request->day_id,[
                    'open_at'=>$request->open_at,
                    'close_at'=>$request->close_at,
                ]); 

            DB::commit();

            return $this->sendResponse($restaurant->days->toArray(), 'Day save on restaurants successfully');

        } catch (\Throwable $th) {
            DB::rollback();
            return $this->sendError($th->getMessage());

        }
    }

    public function daysUpdate(Request $request,$restaurantId,$dayId)
    {
        $this->validate($request, [
            'close_at' => 'required',
            'open_at' => 'required',
        ]);
      
        try {
            DB::beginTransaction();
                $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
                $restaurant->days()->updateExistingPivot([$dayId],[
                    'open_at'=>$request->open_at,
                    'close_at'=>$request->close_at,
                ],false);

            DB::commit();
            return $this->sendResponse($restaurant->days->toArray(), 'Day update on restaurants successfully');

        } catch (\Throwable $th) {
            DB::rollback();
            return $this->sendError($th->getMessage());

        }
    }


    public function daysDestroy($restaurantId,$dayId)
    {   
        try {
            DB::beginTransaction();
                $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
                $restaurant->days()
                ->detach($dayId);
            DB::commit();
            return $this->sendResponse($restaurant->days->toArray(), 'Day delete from restaurants successfully');

        } catch (\Throwable $th) {
            DB::rollback();
            return $this->sendError($th->getMessage());

        }
    }
}
