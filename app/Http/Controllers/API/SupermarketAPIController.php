<?php

namespace App\Http\Controllers\API;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UploadRepository;
use App\Criteria\Restaurants\NearCriteria;
use App\Repositories\RestaurantRepository;
use App\Repositories\CustomFieldRepository;
use App\Criteria\Restaurants\ActiveCriteria;
use App\Criteria\Restaurants\PopularCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Criteria\Restaurants\RestaurantsOfCuisinesCriteria;
use App\Criteria\Restaurants\SupermarketCriteria;
use App\Http\Controllers\API\Restaurant\Resources\Restaurant as RestaurantResource;

class SupermarketAPIController extends Controller
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
        try {
            $this->restaurantRepository->pushCriteria(new RequestCriteria($request));
            $this->restaurantRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->restaurantRepository->pushCriteria(new SupermarketCriteria($request));
            $this->restaurantRepository->pushCriteria(new RestaurantsOfCuisinesCriteria($request));
            if ($request->has('popular')) {
                $this->restaurantRepository->pushCriteria(new PopularCriteria($request));
            } else {
                $this->restaurantRepository->pushCriteria(new NearCriteria($request));
            }
            $this->restaurantRepository->pushCriteria(new ActiveCriteria());
            $restaurants = $this->restaurantRepository->all();

            return RestaurantResource::collection($restaurants);
                // ->filter(function ($restaurant) {
                //     return $restaurant->getDistance()['distance']['distance']['value'] <= (float)setting('range_restaurants_for_customers') * 1000; // range km , so I change it to meters
                // })->sortBy(function ($restaurant) {
                //     return $restaurant->getDistance()['distance']['distance']['value'] ?? null;
                // })
                // ->values();
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
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
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }
}
