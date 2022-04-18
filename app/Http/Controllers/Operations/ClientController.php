<?php

namespace App\Http\Controllers\Operations;

use App\Models\Note;
use App\Models\Order;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Repositories\NoteRepository;
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
    private $noteRepository;
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

      public function __construct(NoteRepository $noteRepo,OrderRepository $orderRepo,UserRepository $userRepo, RoleRepository $roleRepo, UploadRepository $uploadRepo,
                                  CustomFieldRepository $customFieldRepo)
      {
          parent::__construct();
          $this->userRepository = $userRepo;
          $this->roleRepository = $roleRepo;
          $this->uploadRepository = $uploadRepo;
          $this->customFieldRepository = $customFieldRepo;
          $this->orderRepository = $orderRepo;
          $this->noteRepository = $noteRepo;


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
    public function create()
    {

        $rolesSelected = [];
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $html = generateCustomField($customFields);
        }

        return view('operations.client.create')
            // ->with("role", $role)
            ->with("customFields", isset($html) ? $html : false)
            ->with("rolesSelected", $rolesSelected);
    }
     /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }

        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input['roles'] =  ['client'];
        $input['password'] = Hash::make($input['password']);
        $input['api_token'] = str_random(124);
        $input['activated_at'] = now();

        try {
            $user = $this->userRepository->create($input);
            $user->syncRoles($input['roles']);
            $user->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success('saved successfully.');

        return redirect(route('operations.users.index'));
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
        $this->getData($userId,$user,$role,$rolesSelected,$customFieldsValues,$customFields,$html);
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
        $this->getData($userId,$user,$role,$rolesSelected,$customFieldsValues,$customFields,$html);
        return $orderDataTable->with('id', $userId)->render('operations.client.profile.orders', compact('html','customFields','customFieldsValues','user','role','rolesSelected'));
 
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
        $this->getData($userId,$user,$role,$rolesSelected,$customFieldsValues,$customFields,$html);
    
        return $favoriteDataTable->with('userId', $userId)->render('operations.client.profile.favorites', compact('html','customFields','customFieldsValues','user','role','rolesSelected'));

    }
      /**
     * Display the specified Favorite.
     *
     * @param int $id
     *
     * @return Response
     */
    public function notes(NoteDataTable $noteDataTable,$id)
    {
        $this->getData($id,$user,$role,$rolesSelected,$customFieldsValues,$customFields,$html);
        return $noteDataTable->with('id', $id)->render('operations.client.profile.notes', compact('html','customFields','customFieldsValues','user','role','rolesSelected'));

    }
    public function createNote($userId)
    {
        $user = $this->userRepository->findWithoutFail($userId);

       return view('operations.client.profile.createNote',compact('user'));
    }
    public function storeNote(Request $request,$userId)
    {
        $this->validate($request, [
            'text' => 'required',
        ]);
        try {
            $this->noteRepository->create([
                'from_user_id'=>auth()->user()->id,
                'to_user_id'=>$userId,
                'text'=>$request->text
            ]);
            Flash::success('Creat note successfully.');
            return redirect(route('operations.users.profile.notes',$userId));
        } catch (\Throwable $th) {
            Flash::success('Creat note error.');
            return redirect(route('operations.users.profile.notes',$userId));
        }
    }
    public function destroyNote($userId,$noteId)
    {
       try {
            $note = $this->noteRepository->findWithoutFail($noteId);
            $note->delete();
            Flash::success('Delete note successfully.');
            return redirect(route('operations.users.profile.notes',$userId,$noteId));
       } catch (\Throwable $th) {
        Flash::success('Creat note error.');
        return redirect(route('operations.users.profile.notes',$userId,$noteId));
       }
    }
    
    // public function createNote($userId)
    // {
    //     $user = $this->userRepository->findWithoutFail($userId);

    //    return view('operations.client.profile.createNote',compact('user'));
    // }

    // public function storeNote(Request $request,$userId)
    // {
    //     $this->validate($request, [
    //         'text' => 'required',
    //     ]);
    //     try {
    //         $this->noteRepository->create([
    //             'from_user_id'=>auth()->user()->id,
    //             'to_user_id'=>$userId,
    //             'text'=>$request->text
    //         ]);
    //         Flash::success('Creat note successfully.');
    //         return redirect(route('operations.users.profile.notes',$userId));
    //     } catch (\Throwable $th) {
    //         Flash::error('Creat note error.'.$th);
    //         return redirect(route('operations.users.profile.notes',$userId));
    //     }
    // }
    // public function destroyNote($userId,$noteId)
    // {
    //    try {
    //         $note = $this->noteRepository->findWithoutFail($noteId);
    //         $note->delete();
    //         Flash::success('Delete note successfully.');
    //         return redirect(route('operations.users.profile.notes',$userId,$noteId));
    //    } catch (\Throwable $th) {
    //     Flash::success('Creat note error.');
    //     return redirect(route('operations.users.profile.notes',$userId,$noteId));
    //    }
    // }
       /**
     * Display the specified Favorite.
     *
     * @param int $id
     *
     * @return Response
     */
    public function coupons(CouponsDataTable $couponDataTable,$userId)
    {
        $this->getData($userId,$user,$role,$rolesSelected,$customFieldsValues,$customFields,$html);
        return $couponDataTable->with('userId', $userId)->render('operations.client.profile.coupons', compact('html','customFields','customFieldsValues','user','role','rolesSelected'));
    }
    public function address(DeliveryAddressDataTable $deliveryAddressDataTable,$userId)
    {
        $this->getData($userId,$user,$role,$rolesSelected,$customFieldsValues,$customFields,$html);
        return $deliveryAddressDataTable->with('userId', $userId)->render('operations.client.profile.address', compact('html','customFields','customFieldsValues','user','role','rolesSelected'));
    }
    public function statistics($userId)
    {
        $this->getData($userId,$user,$role,$rolesSelected,$customFieldsValues,$customFields,$html);
        $data['total_money']=0;
        $data['orderCount']=Order::where('user_id',$userId)->where('order_status_id',1)->count();
        $data['orderCanceled']=Order::where('user_id',$userId)->where('order_status_id',110)
                            ->orWhere('order_status_id',120)
                            ->orWhere('order_status_id',130)
                            ->orWhere('order_status_id',140)
                            ->count();
        $data['visited']=Order::where('user_id',$userId)->groupBy('restaurant_id')->count();
        $data['total_money']=0;
        
        foreach (Order::where('user_id',$userId)->where('order_status_id',1)->get() as $order) {
            $data['total_money']+=$order->calculateOrderTotal()["total"];
        }
        return view('operations.client.profile.statistics', compact('html','customFields','customFieldsValues','data','user','role','rolesSelected'));

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
            ->with('user', $user)
            // ->with("role", $role)
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
            return redirect()->back();
        }
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect()->back();
        }

        $user = $this->userRepository->findWithoutFail($id);


        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.profile'));
        }
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input = $request->all();
       
        if (empty($input['password'])) {
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
          
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }


        Flash::success($user->name.'updated successfully.');
        return redirect(route('operations.users.index'));

        // return redirect()->back();

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
            return redirect(route('operations.users.index'));
        }
        if ($user->orders()->exists()) {
            Flash::error($user->name.trans('lang.error_user_have_orders'));
            return redirect(route('operations.users.index'));
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
    public function getData($userId,&$user=null,&$role=null,&$rolesSelected=null,&$customFieldsValues=null,&$html=null,&$customFields)
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
    }
}
