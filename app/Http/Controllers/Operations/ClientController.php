<?php

namespace App\Http\Controllers\Operations;

use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Log;
use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\OrderRepository;
use App\DataTables\FoodOrderDataTable;
use App\Repositories\UploadRepository;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Response;
use App\Repositories\CustomFieldRepository;
use App\DataTables\Operations\NoteDataTable;
use App\Criteria\Orders\OrdersOfUserCriteria;
use App\DataTables\Operations\OrderDataTable;
use App\DataTables\Operations\ClientDataTable;
use App\DataTables\Operations\CouponsDataTable;
use App\DataTables\Operations\FavoriteDataTable;
use Prettus\Validator\Exceptions\ValidatorException;
use App\DataTables\Operations\DeliveryAddressDataTable;

class ClientController extends Controller
{

    /** 
     * @var  OrderRepository 
     * */
    private $orderRepository;

    /** 
     * @var  UserRepository 
     * */
    private $userRepository;
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    private $uploadRepository;
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

      public function __construct(OrderRepository $orderRepo,UserRepository $userRepo, RoleRepository $roleRepo, UploadRepository $uploadRepo,
                                  CustomFieldRepository $customFieldRepo)
      {
          parent::__construct();
          $this->userRepository = $userRepo;
          $this->roleRepository = $roleRepo;
          $this->uploadRepository = $uploadRepo;
          $this->customFieldRepository = $customFieldRepo;
          $this->orderRepository = $orderRepo;

      }
     /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(ClientDataTable $userDataTable)
    {
        return $userDataTable->render('operations.client.index');
    }
      /**
     * Display the specified Order.
     *
     * @param int $id
     * @param FoodOrderDataTable $foodOrderDataTable
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */

    public function viewOrders(FoodOrderDataTable $foodOrderDataTable, $userId,$orderId)
    {
        $user = $this->userRepository->findWithoutFail($userId);
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $this->orderRepository->pushCriteria(new OrdersOfUserCriteria($userId));
        $order = $this->orderRepository->findWithoutFail($orderId);
        if (empty($order)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.order')]));

            return redirect(route('orders.index'));
        }
        $subtotal = 0;

        foreach ($order->foodOrders as $foodOrder) {
            foreach ($foodOrder->extras as $extra) {
                $foodOrder->price += $extra->price;
            }
            $subtotal += $foodOrder->price * $foodOrder->quantity;
        }

        $total = $subtotal + $order['delivery_fee'];
        $taxAmount = $total * $order['tax'] / 100;
        $total += $taxAmount - $order->delivery_coupon_value - $order->restaurant_coupon_value;
        $foodOrderDataTable->id = $orderId;

        return $foodOrderDataTable->render('operations.client.profile.view_order',compact('order','total','subtotal','taxAmount','user','role','rolesSelected') );
    }

     /**
     * Display a listing of the User.b
     *
     * @param OrderUserDataTable $userDataTable
     * @return Response
     */
    public function orders(OrderDataTable $orderDataTable,$userId)
    {
        $user = $this->userRepository->findWithoutFail($userId);
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return $orderDataTable->with('id', $userId)->render('operations.client.profile.orders', compact('user','role','rolesSelected'));
 
    }
     /**
     * Display the specified Favorite.
     *
     * @param int $id
     *
     * @return Response
     */
    public function favorites(FavoriteDataTable $favoriteDataTable,$userId)
    {
        $user = $this->userRepository->findWithoutFail($userId);
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return $favoriteDataTable->with('userId', $userId)->render('operations.client.profile.favorites', compact('user','role','rolesSelected'));

    }
      /**
     * Display the specified Favorite.
     *
     * @param int $id
     *
     * @return Response
     */
    public function notes(NoteDataTable $noteDataTable,$userId)
    {
        $user = $this->userRepository->findWithoutFail($userId);
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return $noteDataTable->with('userId', $userId)->render('operations.client.profile.notes', compact('user','role','rolesSelected'));

    }
       /**
     * Display the specified Favorite.
     *
     * @param int $id
     *
     * @return Response
     */
    public function coupons(CouponsDataTable $couponDataTable,$userId)
    {
        $user = $this->userRepository->findWithoutFail($userId);
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return $couponDataTable->with('userId', $userId)->render('operations.client.profile.coupons', compact('user','role','rolesSelected'));

        // return view('operations.client.profile.coupons', compact('user','role','rolesSelected'));

    }
    public function address(DeliveryAddressDataTable $deliveryAddressDataTable,$userId)
    {
        $user = $this->userRepository->findWithoutFail($userId);
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return $deliveryAddressDataTable->with('userId', $userId)->render('operations.client.profile.address', compact('user','role','rolesSelected'));

    }
    public function setAddressDefault($userId,$addressId)
    {
        try {
            DeliveryAddress::where('user_id', $userId)->update(['is_default'=>0]);
            DeliveryAddress::where('id', $addressId)->update(['is_default'=>1]);
            Flash::success(__('lang.updated_successfully',['operator'=>'Address']));
        } catch (\Throwable $th) {
            Flash::error($th->getMessage());
        }
        return redirect(route('operations.users.profile.address',$userId));
    }
    public function deleteAddress($userId,$addressId)
    {
        try {
            $address=DeliveryAddress::find($addressId);
            if ($address->orders()->exists()) {
                Flash::error('You have order on this address');
            }else {
                $address->delete();
                Flash::success(__('lang.deleted_successfully'));
            }
        } catch (\Throwable $th) {
            Flash::error($th->getMessage());
        }
      
       

        return redirect(route('operations.users.profile.address',$userId));
    }
    
    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('users.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);
        unset($user->password);
        $html = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }


        if ($user->hasRole('driver')) {
            $doc = app('firebase.firestore')->getFirestore()->collection('drivers')
                ->document(102/* $user->user_id */)->snapshot(); //->get();

            if ($doc->exists()) {
                $d = $doc->data();
                $user->longitude = $d['longitude'];
                $user->latitude = $d['latitude'];
            }
        }


        return view('operations.client.edit')
            ->with('user', $user)->with("role", $role)
            ->with("rolesSelected", $rolesSelected)
            ->with("customFields", $html);
    }

     /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.profile'));
        }
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('users.profile'));
        }

        $user = $this->userRepository->findWithoutFail($id);


        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.profile'));
        }
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input = $request->all();
        if (!auth()->user()->can('permissions.index')) {
            unset($input['roles']);
        } else {
        $input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        }
        if (empty($input['password'])) {
            unset($input['password']);
        } else {
            $input['password'] = Hash::make($input['password']);
        }
        if ($request->has('activated_at') && $input['activated_at'] == 1) {
            $input['activated_at'] = now();
        }
        try {
            $user = $this->userRepository->update($input, $id);
            if (empty($user)) {
                Flash::error('User not found');
                return redirect(route('users.profile'));
            }
            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            if (auth()->user()->can('permissions.index')) {
            $user->syncRoles($input['roles']);
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }


        Flash::success('User updated successfully.');

        return redirect()->back();

    }


  /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('operations.users.index'));
    }
    /**
     * Display a user profile.
     *
     * @param
     * @return Response
     */
    public function profile($id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        unset($user->password);
        $customFields = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        //dd($customFieldsValues);
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFields = generateCustomField($customFields, $customFieldsValues);
        }
        return view('operations.client.profile.index', compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues']));
    }
}
