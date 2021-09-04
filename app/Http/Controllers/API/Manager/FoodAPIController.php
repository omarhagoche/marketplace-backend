<?php

namespace App\Http\Controllers\API\Manager;


use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Repositories\CustomFieldRepository;
use App\Repositories\FoodRepository;
use App\Repositories\UploadRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Auth;
use DB;

/**
 * Class FoodController
 * @package App\Http\Controllers\API
 */
class FoodAPIController extends Controller
{

    private $restaurantIds = [];

    /** @var  FoodRepository */
    private $foodRepository;
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;


    public function __construct(FoodRepository $foodRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->foodRepository = $foodRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }


    /**
     * Get array of restaurant ids for auth users (that auth user linked to them)
     * 
     * @return array
     */
    protected function getRestaurantIds()
    {
        if (empty($this->restaurantIds)) {
            $this->restaurantIds = Auth::user()->restaurants()->allRelatedIds()->toArray();
        }
        return $this->restaurantIds;
    }


    /**
     * Display a listing of the Food.
     * GET|HEAD /foods
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $foods =   Food::with('media')
            ->whereIn('restaurant_id', $this->getRestaurantIds())
            ->get();
        $foods->makeHidden(['restaurant']); // to skip bring restaurant relationship model
        return $this->sendResponse($foods->toArray(), 'Foods retrieved successfully');
    }

}
