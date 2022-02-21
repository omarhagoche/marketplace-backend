<?php

namespace App\Http\Controllers\Operations;

use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Entities\UploadRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Route;
use App\Repositories\CuisineRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\CustomFieldRepository;
use App\Criteria\Restaurants\RestaurantsOfUserCriteria;

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

    public function __construct(RestaurantRepository $restaurantRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, UserRepository $userRepo, CuisineRepository $cuisineRepository)
    {
        parent::__construct();
        $this->restaurantRepository = $restaurantRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->userRepository = $userRepo;
        $this->cuisineRepository = $cuisineRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $this->restaurantRepository->pushCriteria(new RestaurantsOfUserCriteria(auth()->id()));
        $restaurant = $this->restaurantRepository->findWithoutFail($id);
        if (empty($restaurant)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.restaurant')]));
            return redirect(route('restaurants.index'));
        }
        $cuisine = $this->cuisineRepository->pluck('name', 'id');
        $cuisinesSelected = $restaurant->cuisines()->pluck('cuisines.id')->toArray();
        $customFieldsValues = $restaurant->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->restaurantRepository->model());
        $hasCustomField = in_array($this->restaurantRepository->model(), setting('custom_field_models', []));
        // dd($hasCustomField);
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('operations.restaurantProfile.edit',compact('restaurant','customFields','cuisine','cuisinesSelected'));
        // ->with('restaurant', $restaurant)->with("customFields", isset($html) ? $html : false)->with('cuisine', $cuisine)->with('cuisinesSelected', $cuisinesSelected);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function users(UserDataTable $userDataTable,$restaurantId)
    {
        // $user->restaurants()->attach($request->restaurant_id, [
        //     'enable_notifications' => $request->get('enable_notifications', 1)
        // ]);
        //$restaurant = auth()->user()->restaurants()->where('id', $request->restaurant_id)->first();

        return $userDataTable->render('operations.restaurantProfile.users.index');

    }
}
