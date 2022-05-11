<?php

/**
 * File name: RestaurantController.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\Operations;

use Flash;
use App\Models\Day;
use App\Models\Food;
use App\Models\User;
use App\Models\Extra;
use App\Models\ExtraFood;
use App\Models\ExtraGroup;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use Illuminate\Support\Facades\DB;
use App\Repositories\DayRepository;
use Illuminate\Support\Facades\Log;
use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Repositories\FoodRepository;
use App\Repositories\NoteRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\ExtraRepository;
use Illuminate\Support\Facades\Route;
use App\Criteria\Users\AdminsCriteria;
use App\Events\RestaurantChangedEvent;
use App\Repositories\UploadRepository;
use App\Criteria\Users\ClientsCriteria;
use App\Criteria\Users\DriversCriteria;
use App\Repositories\CuisineRepository;
use App\Criteria\Users\ManagersCriteria;
use App\Http\Requests\CreateFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Response;
use App\Repositories\ExtraGroupRepository;
use App\Repositories\RestaurantRepository;
use App\Criteria\Foods\FoodsOfUserCriteria;
use App\DataTables\Operations\DayDataTable;
use App\Repositories\CustomFieldRepository;
use App\DataTables\Operations\NoteDataTable;
use App\DataTables\Operations\OrderDataTable;
use App\Http\Requests\CreateRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Criteria\Users\ManagersClientsCriteria;
use App\DataTables\RequestedRestaurantDataTable;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Criteria\Restaurants\RestaurantsOfUserCriteria;
use App\DataTables\Operations\OrderFoodBookingDataTable;
use App\DataTables\Operations\RestaurantSearchDataTable;
use App\Models\Order;

class RestaurantController extends Controller
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
    private $noteRepository;
    private $dayRepository;


    public function __construct(DayRepository $dayRepo, NoteRepository $noteRepo, ExtraRepository $extraRepository, ExtraGroupRepository $extraGroupRepository, CategoryRepository $categoryRepository, FoodRepository $foodRepository, RoleRepository $roleRepository, RestaurantRepository $restaurantRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, UserRepository $userRepo, CuisineRepository $cuisineRepository)
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
        $this->noteRepository = $noteRepo;
        $this->dayRepository = $dayRepo;
    }

    /**
     * Display a listing of the Restaurant.
     *
     * @param RestaurantSearchDataTable $restaurantDataTable
     * @return Response
     */
    public function index(RestaurantSearchDataTable $restaurantDataTable)
    {
        return $restaurantDataTable->render('operations.restaurants.index');
    }

    /**
     * Display a listing of the Restaurant.
     *
     * @param RestaurantDataTable $restaurantDataTable
     * @return Response
     */
    public function requestedRestaurants(RequestedRestaurantDataTable $requestedRestaurantDataTable)
    {
        return $requestedRestaurantDataTable->render('operations.restaurants.requested');
    }

    /**
     * Show the form for creating a new Restaurant.
     *
     * @return Response
     */
    public function create()
    {
        $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $cuisine = $this->cuisineRepository->pluck('name', 'id');
        $usersSelected = [];
        $driversSelected = [];
        $cuisinesSelected = [];
        $deliveryPriceType = [];
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('operations.restaurants.create')->with("customFields", isset($html) ? $html : false)
            ->with("deliveryPriceType", $deliveryPriceType)
            ->with("user", $user)->with("drivers", $drivers)->with("usersSelected", $usersSelected)->with("driversSelected", $driversSelected)->with('cuisine', $cuisine)->with('cuisinesSelected', $cuisinesSelected);
    }

    /**
     * Store a newly created Restaurant in storage.
     *
     * @param CreateRestaurantRequest $request
     *
     * @return Response
     */
    public function store(CreateRestaurantRequest $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:32',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20|unique:users,phone_number',

        ]);
        $input = $request->all();
        $input['delivery_range'] = $request->delivery_range ?? 10;
        if (auth()->user()->hasRole(['manager', 'client'])) {
            $input['users'] = [auth()->id()];
        }
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        try {
            $restaurant = $this->restaurantRepository->create($input);
            // get day ids
            $DayIds = Day::pluck('id');
            // insert for each restaurant all days 
            $restaurant->days()->attach($DayIds);
            $restaurant->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($restaurant, 'image');
            }
            $user = User::Create([
                'email' => $request['email'],
                'name' => $request['name'],
                'phone_number' => $request['phone'],
                'active' => true,
                'password' => Hash::make($request['email'])
            ]);

            $defaultRoles = $this->roleRepository->findByField('name', 'manager');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);
            $user->restaurants()->attach($restaurant->id);
            event(new RestaurantChangedEvent($restaurant, $restaurant));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput();
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.restaurant')]));

        return redirect(route('operations.restaurant_profile_edit', $restaurant->id));
    }

    /**
     * Display the specified Restaurant.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show($id)
    {
        $this->restaurantRepository->pushCriteria(new RestaurantsOfUserCriteria(auth()->id()));
        $restaurant = $this->restaurantRepository->findWithoutFail($id);

        if (empty($restaurant)) {
            Flash::error('Restaurant not found');

            return redirect(route('operations.restaurant_profile.index'));
        }

        return view('operations.restaurants.show')->with('restaurant', $restaurant);
    }

    /**
     * Show the form for editing the specified Restaurant.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit($id)
    {
        $this->restaurantRepository->pushCriteria(new RestaurantsOfUserCriteria(auth()->id()));
        $restaurant = $this->restaurantRepository->findWithoutFail($id);

        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }
        if ($restaurant['active'] == 0) {
            $user = $this->userRepository->getByCriteria(new ManagersClientsCriteria())->pluck('name', 'id');
        } else {
            $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        }
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $cuisine = $this->cuisineRepository->pluck('name', 'id');


        $usersSelected = $restaurant->users()->pluck('users.id')->toArray();
        $driversSelected = $restaurant->drivers()->pluck('users.id')->toArray();
        $cuisinesSelected = $restaurant->cuisines()->pluck('cuisines.id')->toArray();

        $customFieldsValues = $restaurant->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('operations.restaurants.edit')
            ->with('id', $id)
            ->with('restaurant', $restaurant)
            ->with("customFields", isset($html) ? $html : false)
            ->with("user", $user)
            ->with("drivers", $drivers)
            ->with("usersSelected", $usersSelected)
            ->with("driversSelected", $driversSelected)
            ->with('cuisine', $cuisine)
            ->with('cuisinesSelected', $cuisinesSelected);
    }

    /**
     * Update the specified Restaurant in storage.
     *
     * @param int $id
     * @param UpdateRestaurantRequest $request
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update($id, UpdateRestaurantRequest $request)
    {
        $this->restaurantRepository->pushCriteria(new RestaurantsOfUserCriteria(auth()->id()));
        $oldRestaurant = $this->restaurantRepository->findWithoutFail($id);

        if (empty($oldRestaurant)) {
            Flash::error('Restaurant not found');
            return redirect(route('restaurants.index'));
        }
        $input = $request->all();
        array_push($input['users'], ...$input['drivers']); //thhis line for push drivers ids with users 
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        try {

            $restaurant = $this->restaurantRepository->update($input, $id);
            $input['drivers'] ? $restaurant->drivers()->sync($input['drivers']) : ''; //Assigning drivers to the restaurant
            $input['users'] ? $restaurant->users()->sync($input['users']) : ''; //Assigning users to the restaurant
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($restaurant, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $restaurant->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new RestaurantChangedEvent($restaurant, $oldRestaurant));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.restaurant')]));

        return redirect(route('operations.restaurant_profile_edit', $id));
    }

    /**
     * Remove the specified Restaurant from storage.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy($id)
    {
        if (!env('APP_DEMO', false)) {
            $this->restaurantRepository->pushCriteria(new RestaurantsOfUserCriteria(auth()->id()));
            $restaurant = $this->restaurantRepository->findWithoutFail($id);

            if (empty($restaurant)) {
                Flash::error('Restaurant not found');

                return redirect(route('restaurants.index'));
            }

            $this->restaurantRepository->delete($id);

            Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.restaurant')]));
        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }
        return redirect(route('operations.restaurant_profile.index'));
        
    }

    /**
     * Remove Media of Restaurant
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $restaurant = $this->restaurantRepository->findWithoutFail($input['id']);
        try {
            if ($restaurant->hasMedia($input['collection'])) {
                $restaurant->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function users(UserDataTable $userDataTable, $id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }
        $customFieldsValues = $restaurant->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return $userDataTable
            ->with(['id' => $id, 'restaurant' => $restaurant, "customFields" => isset($html) ? $html : false])
            ->render('operations.restaurantProfile.users.index', compact('id', 'restaurant', 'customFields'));
    }

    public function usersCreate($id, $userId = null)
    {
        // dd($id,$userId);
        if ($userId != null) $user = User::find($userId);
        else $user = null;
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        $role = $this->roleRepository->where('name', '!=', 'admin')->where('name', '!=', 'client')->pluck('name', 'name');
        $rolesSelected = isset($userId) ? $user->getRoleNames()->toArray() : [];
        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }
        $customFieldsValues = $restaurant->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $html = generateCustomField($customFields);
        }

        return view('operations.restaurantProfile.users.create', compact('id', 'userId', 'user', 'role', 'rolesSelected', 'restaurant', 'customFields'))
            ->with("customFields", isset($html) ? $html : false);
    }

    public function usersStore(Request $request, $id, $userId = null)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|min:3|max:32',
        ]);
        if ($userId == null) {
            $this->validate($request, [
                'email' => 'nullable|email|unique:users',
                'password' => 'required|min:6|max:32',
                'phone_number' => ['required', new PhoneNumber, 'unique:users'],

            ]);
        }

        // dd($request->except(['_token']));
        try {
            DB::transaction(function () use ($request, $id, $userId) {
                // $date=;
                $input = $request->all();
                // $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                $restaurant = $this->restaurantRepository->findWithoutFail($id);

                $input['roles'] = $input['roles'];
                $input['password'] = Hash::make($input['password']);
                // $input['api_token'] = str_random(124);
                $input['activated_at'] = now();

                try {
                    $user = User::updateOrCreate(['id' => $userId], $input);
                    $user->syncRoles($input['roles']);
                    // $user->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
                    if ($input['roles'][0] == 'driver') {
                        if ($restaurant->drivers()->where('user_id', $user->id)->exists()) {
                            $restaurant->drivers()->sync($user->id);
                        } else {
                            $restaurant->drivers()->attach($user->id);
                        }
                    }
                    if ($input['roles'][0] == 'manager') {
                        if ($restaurant->users()->where('user_id', $user->id)->exists()) {
                            $restaurant->users()->sync($user->id);
                        } else {
                            $restaurant->users()->attach($user->id);
                        }
                    }
                    if (isset($input['avatar']) && $input['avatar']) {
                        $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                        $mediaItem = $cacheUpload->getMedia('avatar')->first();
                        $mediaItem->copy($user, 'avatar');
                    }
                    // event(new UserRoleChangedEvent($user));
                } catch (ValidatorException $e) {
                    Flash::error($e->getMessage());
                }

                // $user=User::firstOrCreate($request['email'],$request->except(['_token','avatar','email']));
                // $defaultRoles = $this->roleRepository->findByField('default', '1');
                // $defaultRoles = $defaultRoles->pluck('name')->toArray();
                // $user->assignRole($defaultRoles);
                // $user->restaurants()->attach($id);

            });

            Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.users')]));
        } catch (\Throwable $th) {
            dd($request->all(), $th);
            Flash::error($th);
            return  redirect()->back()->withInput();
        }
        return redirect(route('operations.restaurant_profile.users', $id));
    }

    public function usersDestroy($id, $userId)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect()->back();
        }

        $user = $this->userRepository->findWithoutFail($userId);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('operations.restaurant_profile.users', $id));
        }
        try {
            DB::transaction(function () use ($id, $userId) {
                $this->userRepository->delete($userId);
            });
            Flash::success('User deleted successfully.');
            return redirect(route('operations.restaurant_profile.users', $id));
        } catch (\Throwable $th) {
            Flash::error('Have error on User delete form restaurant');
            return redirect(route('operations.restaurant_profile.users', $id));
        }
    }

    public function orders(OrderFoodBookingDataTable $orderDataTable, $id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);

        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }

        $customFieldsValues = $restaurant->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));

        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return $orderDataTable
            ->with([
                'id' => $id,
                'restaurant' => $restaurant,
                "customFields" => isset($html) ? $html : false
            ])
            ->render('operations.restaurantProfile.orders.index', compact('id', 'restaurant', 'customFields'));
    }

    public function notes(NoteDataTable $noteDataTable, $id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);

        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }

        $customFieldsValues = $restaurant->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));

        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return $noteDataTable->with([
            'id' => $id,
            'restaurant' => $restaurant,
            "customFields" => isset($html) ? $html : false
        ])->render('operations.restaurantProfile.notes.index', compact('id', 'restaurant', 'customFields'));
    }

    public function createNote($id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);

        return view('operations.restaurantProfile.notes.create', compact('restaurant'));
    }

    public function storeNote(Request $request, $id)
    {
        $this->validate($request, [
            'text' => 'required',
        ]);
        try {
            $this->noteRepository->create([
                'from_user_id' => auth()->user()->id,
                'restaurant_id' => $id,
                'text' => $request->text
            ]);
            Flash::success('Creat note successfully.');
            return redirect(route('operations.restaurant_profile.note.index', $id));
        } catch (\Throwable $th) {
            Flash::error('Creat note error');
            return redirect(route('operations.restaurant_profile.note.create', $id));
        }
    }

    public function destroyNote($restaurantId, $noteId)
    {
        try {
            $note = $this->noteRepository->findWithoutFail($noteId);
            $note->delete();
            Flash::success('Delete note successfully.');
            return redirect(route('operations.restaurant_profile.note.index', $restaurantId));
        } catch (\Throwable $th) {
            Flash::error('Delete note error.');
            return redirect(route('operations.restaurant_profile.note.index', $restaurantId));
        }
    }

    public function days(DayDataTable $daysDataTable, $id)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }
        return $daysDataTable
            ->with(['id' => $id, 'restaurant' => $restaurant])
            ->render('operations.restaurantProfile.days.index', compact('id', 'restaurant'));
    }

    public function daysEdit($restaurantId, $dayId)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
        $day = $restaurant->days()->where('day_id', $dayId)->first();
        return view('operations.restaurantProfile.days.edit', compact('restaurant', 'day'));
    }

    public function daysUpdate(Request $request, $restaurantId, $dayId)
    {
        $this->validate($request, [
            'close_at' => 'required',
            'open_at' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
            $restaurant->days()->updateExistingPivot([$dayId], [
                'open_at' => $request->open_at,
                'close_at' => $request->close_at,
            ], false);
            Flash::success('Update day Time successfully.');
            DB::commit();

            return redirect(route('operations.restaurant_profile.days.index', ['id' => $restaurantId]));
        } catch (\Throwable $th) {
            DB::rollback();
            Flash::error('Creat note error');
            return redirect(route('operations.restaurant_profile.note.create', ['id' => $restaurantId]));
        }
    }

    public function daysCreate($restaurantId)
    {
        $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
        $days = $this->dayRepository->pluck('name', 'id');
        return view('operations.restaurantProfile.days.create', compact('restaurant', 'days'));
    }

    public function daysStore(Request $request, $restaurantId)
    {
        $this->validate($request, [
            'day_id' => 'required',
            'close_at' => 'required',
            'open_at' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
            $restaurant->days()->attach($request->day_id, [
                'open_at' => $request->open_at,
                'close_at' => $request->close_at,
            ], false);
            Flash::success('Create day Time successfully.');
            DB::commit();

            return redirect(route('operations.restaurant_profile.days.index', ['id' => $restaurantId]));
        } catch (\Throwable $th) {
            DB::rollback();
            Flash::error('Create note error');
            return redirect(route('operations.restaurant_profile.note.create', ['id' => $restaurantId]));
        }
    }
    public function daysDestroy($restaurantId, $dayId)
    {
        try {
            DB::beginTransaction();
            $restaurant = $this->restaurantRepository->findWithoutFail($restaurantId);
            $restaurant->days()
                ->detach($dayId);
            Flash::success('Delete day Time successfully.');
            DB::commit();

            return redirect(route('operations.restaurant_profile.days.index', ['id' => $restaurantId]));
        } catch (\Throwable $th) {
            DB::rollback();
            Flash::error('Delete day Time error' . $th);
            return redirect(route('operations.restaurant_profile.days.index', ['id' => $restaurantId]));
        }
    }
}
