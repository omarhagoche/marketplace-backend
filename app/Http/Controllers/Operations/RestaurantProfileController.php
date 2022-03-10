<?php

namespace App\Http\Controllers\Operations;

use App\Criteria\Foods\FoodsOfUserCriteria;
use App\Criteria\Users\DriversCriteria;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Repositories\UploadRepository;
use App\Repositories\CuisineRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\CustomFieldRepository;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CreateFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\ExtraGroupRepository;
use App\Repositories\ExtraRepository;
use App\Repositories\FoodRepository;

class RestaurantProfileController extends Controller
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
    /**
     * @var UserRepository
    */
    private $userRepository;
    /**
     * @var CuisineRepository
    */
    private $cuisineRepository;

    /**
     * @var FoodRepository
    */
    private $foodRepository;

    /**
     * @var CategoryRepository
    */
    private $categoryRepository;

    /**
     * @var ExtraGroupRepository
    */
    private $extraGroupRepository;
    /**
     * @var ExtraRepository
    */
    private $extraRepository;

    public function __construct(ExtraRepository $extraRepository, ExtraGroupRepository $extraGroupRepository, CategoryRepository $categoryRepository,FoodRepository $foodRepository,RoleRepository $roleRepository,RestaurantRepository $restaurantRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, UserRepository $userRepo, CuisineRepository $cuisineRepository)
    {
        parent::__construct();
        $this->roleRepository = $roleRepository;
        $this->restaurantRepository = $restaurantRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->userRepository = $userRepo;
        $this->cuisineRepository = $cuisineRepository;
        $this->foodRepository = $foodRepository;
        $this->categoryRepository = $categoryRepository;
        $this->extraGroupRepository = $extraGroupRepository;
        $this->extraRepository = $extraRepository;
    }

    public function restaurantFoodsindex($id) {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        $foods = $this->foodRepository->restaurantFoods($id);
        $category = $this->categoryRepository->pluck('name', 'id');
        $category = [null => "" , $category];
        return view('operations.restaurantProfile.foods.index',compact('id','restaurant','foods','category'));
    }

    /**
     * Show the form for edit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */ 
    public function editProfileRestaurant($id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $driversSelected = $restaurant->drivers()->pluck('users.id')->toArray();

        $cuisine = $this->cuisineRepository->pluck('name', 'id');
        $cuisinesSelected = $restaurant->cuisines()->pluck('cuisines.id')->toArray();
        $customFieldsValues = $restaurant->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('operations.restaurantProfile.edit')
        ->with('driversSelected', $driversSelected)
        ->with('id', $id)
        ->with('drivers', $drivers)
        ->with('restaurant', $restaurant)
        ->with("customFields", isset($html) ? $html : false)
        ->with('cuisine', $cuisine)
        ->with('cuisinesSelected', $cuisinesSelected);
    }

    public function restaurantFoodsCreate($id) {

        $category = $this->categoryRepository->pluck('name', 'id');
        $extra = $this->extraRepository->findByField('restaurant_id', $id)->pluck('name', 'id');
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        $hasCustomField = in_array($this->foodRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->foodRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('operations.restaurantProfile.foods.create',compact('extra','id','restaurant','category'))->with("customFields", isset($html) ? $html : false);
    }
    
    public function restaurantFoodsStore(CreateFoodRequest $request, $id) {
        try {
            DB::beginTransaction();
            $inputFood = $request->all();
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->foodRepository->model());
            $food = $this->foodRepository->create($inputFood);
            $food->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($inputFood['image']) && $inputFood['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($inputFood['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($food, 'image');
            }

            DB::commit();
        } catch (ValidatorException $e) {
            DB::rollback();
            Flash::error($e->getMessage());
        }
        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.food')]));
        return redirect(route('operations.restaurant.foods.create',$id));
    }

    public function restaurantFoodsEdit($id, $food_id) {        
            $this->foodRepository->pushCriteria(new FoodsOfUserCriteria(auth()->id()));
            $food = $this->foodRepository->findWithoutFail($food_id);
            if (empty($food)) {
                Flash::error(__('lang.not_found', ['operator' => __('lang.food')]));
                return redirect(route('foods.index'));
            }
            $extra = $this->extraRepository->findByField('restaurant_id', $id)->pluck('name', 'id');
            $category = $this->categoryRepository->pluck('name', 'id');
            $restaurant = $this->restaurantRepository->findWithoutFail($id);
            $customFieldsValues = $food->customFieldsValues()->with('customField')->get();
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->foodRepository->model());
            $hasCustomField = in_array($this->foodRepository->model(), setting('custom_field_models', []));
            if ($hasCustomField) {
                $html = generateCustomField($customFields, $customFieldsValues);
            }
            $extraGroup = $this->extraGroupRepository->pluck('name', 'id');
            return view('operations.restaurantProfile.foods.edit',compact('extra','id','restaurant','category','extraGroup','food'))->with("customFields", isset($html) ? $html : false);

    }

    public function restaurantFoodsUpdate(UpdateFoodRequest $request, $id,$food_id) { 
        $this->foodRepository->pushCriteria(new FoodsOfUserCriteria(auth()->id()));
        $food = $this->foodRepository->findWithoutFail($food_id);

        if (empty($food)) {
            Flash::error('Food not found');
            return redirect(route('foods.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->foodRepository->model());
        try {
            DB::beginTransaction();  
            $food = $this->foodRepository->update($input, $food_id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($food, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $food->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            DB::commit();
        } catch (ValidatorException $e) {
            DB::rollback();
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.food')]));
        return redirect(route('operations.restaurant.foods.index',$id));
    }
    
    public function restaurantFoodsDelete($id,$restaurantId) {   
        if (!env('APP_DEMO', false)) {
            $this->foodRepository->pushCriteria(new FoodsOfUserCriteria(auth()->id()));
            $food = $this->foodRepository->findWithoutFail($id);
            
            if (empty($food)) {
                Flash::error('Food not found');

                return redirect(route('operations.restaurant.foods.index',$restaurantId));
            }
            
            $this->foodRepository->delete($id);
            
            Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.food')]));
            
        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }
        return redirect(route('operations.restaurant.foods.index',$restaurantId));
    }

    public function restaurantFoodUpdate(Request $request) {   
        $food = $this->foodRepository->findWithoutFail($request->id);
        $food->name = $request->name;
        $food->price = $request->price;
        $food->discount_price = $request->discount_price;
        $food->category_id = $request->category_id;
        $food->package_items_count = $request->package_items_count;
        $food->available = $request->available;
        $food->update();
        return response()->json($food, 200);
    }
}
