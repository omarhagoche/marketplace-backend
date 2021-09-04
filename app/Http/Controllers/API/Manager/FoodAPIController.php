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

    /**
     * Display the specified Food.
     * GET|HEAD /foods/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $food =  Food::with('media')
            ->whereIn('restaurant_id', $this->getRestaurantIds())
            ->findOrFail($id);
        $food->makeHidden(['restaurant']); // to skip bring restaurant relationship model
        return $this->sendResponse($food->toArray(), 'Food retrieved successfully');
    }

    /**
     * Store a newly created Food in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $this->validateData();

        try {
            DB::beginTransaction();

            $food = $this->foodRepository->create($input);
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->foodRepository->model());
            $food->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

            if (isset($input['image']) && $input['image']) {
                upload_image($request->image, $food->id, 'image')
                    ->getMedia('image')
                    ->first()
                    ->copy($food, 'image');

                $food->setHidden(['restaurant']); // skip load restaurant relationship
                $food->load('media'); // load media relationship to load images of food
            }

            DB::commit();
        } catch (ValidatorException $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($food->toArray(), __('lang.saved_successfully', ['operator' => __('lang.food')]));
    }

    /**
     * Validate data and return it with default values for some properties
     * 
     * @param boolean $update
     * @return array 
     */
    protected function validateData($update = false)
    {
        $request = request();
        $rules = [
            'name' => 'required|string|min:3|max:64',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0',
            'description' => 'required|string|min:3|max:1000',
            'ingredients' => 'nullable|string|max:1000',
            'package_items_count' => 'nullable|numeric|min:1',
            'weight' => 'required|string|min:3|max:64',
            'unit' => 'nullable|string|min:1|max:10',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:25600',
            'featured' => 'required|boolean',
            'deliverable' => 'required|boolean',
            'category_id' => 'required|integer|exists:categories,id',
            'restaurant_id' => 'nullable|in_array:' . implode(',', $this->getRestaurantIds())
        ];

        if ($update) {
            $rules['image'] = str_replace('required', 'nullable', $rules['image']);
        }

        $input = $request->validate($rules);
        $input = array_merge($input, [
            'package_items_count' => $request->get('package_items_count', 1),
            'unit' => $request->get('unit', 'g'),
            'restaurant_id' => $request->get('restaurant_id', $this->getRestaurantIds()[0])
        ]);
        return $input;
    }
}
