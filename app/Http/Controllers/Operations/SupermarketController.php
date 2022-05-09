<?php

namespace App\Http\Controllers\Operations;

use Flash;
use App\Models\{Day, Food, User, Restaurant};
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Criteria\Users\{DriversCriteria, ManagersCriteria};
use App\Criteria\Foods\DistinctSupermarketProductsCriteria;
use App\Http\Requests\{CreateRestaurantRequest, UpdateRestaurantRequest};
use App\Events\RestaurantChangedEvent;
use App\Repositories\{DayRepository, FoodRepository, NoteRepository, RoleRepository, UserRepository, ExtraRepository};
use App\Repositories\{UploadRepository, CategoryRepository, ExtraGroupRepository, RestaurantRepository, CustomFieldRepository};
use App\DataTables\Operations\SupermarketDataTable;
use App\Criteria\Restaurants\RestaurantsOfUserCriteria;
use App\Enums\MerchantType;
use Illuminate\Support\Facades\DB;

class SupermarketController extends Controller
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
     * @var FoodRepository
     */
    private $foodRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;



    public function __construct(DayRepository $dayRepo, NoteRepository $noteRepo, ExtraRepository $extraRepository, ExtraGroupRepository $extraGroupRepository, CategoryRepository $categoryRepository, FoodRepository $foodRepository, RoleRepository $roleRepository, RestaurantRepository $restaurantRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, UserRepository $userRepo)
    {
        parent::__construct();
        $this->roleRepository = $roleRepository;
        $this->restaurantRepository = $restaurantRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->userRepository = $userRepo;
        $this->foodRepository = $foodRepository;
        $this->categoryRepository = $categoryRepository;
        $this->extraGroupRepository = $extraGroupRepository;
        $this->extraRepository = $extraRepository;
        $this->noteRepository = $noteRepo;
        $this->dayRepository = $dayRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SupermarketDataTable $supermarketDataTable)
    {
        return $supermarketDataTable->render('operations.supermarkets.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $usersSelected = [];
        $driversSelected = [];
        $deliveryPriceType = [];
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
            $html = generateCustomField($customFields);
        }
        return view(
            'operations.supermarkets.create',
            compact(
                'deliveryPriceType',
                'user',
                'drivers',
                'usersSelected',
                'driversSelected'
            )
        )->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRestaurantRequest $request)
    {
      
        $this->validate($request, [
            'name' => 'required|min:3|max:32',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20|unique:users,phone_number',

        ]);

        $input = $request->all();
        $input = array_merge($input, [
            'merchant_type' => MerchantType::SUPERMARKET,
            'is_restaurant' => 0
        ]);
        
        if (auth()->user()->hasRole(['manager', 'client'])) {
            $input['users'] = [auth()->id()];
        }
        
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        try {
            $supermarket = $this->restaurantRepository->create($input);
            // get day ids
            $DayIds = Day::pluck('id');
            // insert for each restaurant all days 
            $supermarket->days()->attach($DayIds);
            $supermarket->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($supermarket, 'image');
            }
            $user = User::create([
                'email' => $request['email'],
                'name' => $request['name'],
                'phone_number' => $request['phone'],
                'active' => true,
                'password' => Hash::make($request['phone'])
            ]);

            $defaultRoles = $this->roleRepository->findByField('name', 'manager');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);
            $user->restaurants()->attach($supermarket->id);
            event(new RestaurantChangedEvent($supermarket, $supermarket));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput();
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.supermarket')]));

        return redirect(route('operations.supermarkets.edit', $supermarket->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $supermarket = $this->restaurantRepository->find($id);

        if (empty($supermarket)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $driversSelected = $supermarket->drivers()->pluck('users.id')->toArray();
        $users = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        $usersSelected = $supermarket->users()->pluck('users.id')->toArray();

        $customFieldsValues = $supermarket->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('operations.supermarkets.edit')
            ->with('driversSelected', $driversSelected)
            ->with('usersSelected', $usersSelected)
            ->with('id', $id)
            ->with('drivers', $drivers)
            ->with('users', $users)
            ->with('supermarket', $supermarket)
            ->with("customFields", isset($html) ? $html : false);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRestaurantRequest $request, $id)
    {
        $this->restaurantRepository->pushCriteria(new RestaurantsOfUserCriteria(auth()->id()));
        $oldSupermarket = $this->restaurantRepository->findWithoutFail($id);
        if (empty($oldSupermarket)) {
            Flash::error('Restaurant not found');
            return redirect(route('restaurants.index'));
        }
        $input = $request->all();
        array_push($input['users'], ...$input['drivers']); //this line for push drivers ids with users 
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        try {

            $supermarket = $this->restaurantRepository->update($input, $id);
            $input['users'] ? $supermarket->users()->sync($input['users']) : ''; //Assigning users to the restaurant
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($supermarket, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $supermarket->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new RestaurantChangedEvent($supermarket, $oldSupermarket));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.supermarket')]));

        return redirect(route('operations.supermarkets.edit', $id));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /* Get associate products form */
    public function associateProductsForm()
    {
        $products = $this->foodRepository->pushCriteria(new DistinctSupermarketProductsCriteria())->all();
        $supermaket = $this->restaurantRepository->all();
    }

    /* associate products to the supermarket */
    public function associateProducts()
    {
    }

    public function productIndex($id)
    {
        $supermarket = $this->restaurantRepository->supermarketWithProducts($id);
        $categories = $this->categoryRepository->pluck('name', 'id');
        return view('operations.supermarkets.products.index', compact('id', 'supermarket', 'categories'));
    }

    public function productCreate($id)
    {
        $category = $this->categoryRepository->pluck('name', 'id');
        $supermarket = $this->restaurantRepository->findWithoutFail($id);
        return view('operations.supermarkets.products.create', compact('id', 'supermarket', 'category'));
    }

    public function productStore(int $id)
    {
        $supermarket = $this->restaurantRepository->findWithoutFail($id);
        
        try {
            DB::beginTransaction();
            $productData = request()->all();
            $productData['restaurant_id'] = $supermarket->id;
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->foodRepository->model());
            $product = $this->foodRepository->create($productData);
            $product->customFieldsValues()->createMany(getCustomFieldsValues($customFields, request()));
            if (isset($productData['image']) && $productData['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($productData['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($product, 'image');
            }
            DB::commit();
        } catch (ValidatorException $e) {
            DB::rollback();
            Flash::error($e->getMessage());
        }
        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.product')]));
        return redirect(route('operations.supermarkets.products.create', $id));
    }
}
