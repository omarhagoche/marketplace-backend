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
        $foods = Food::with('media', 'category')
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
        $food =  Food::with('media', 'category')
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
                $food->load('media', 'category'); // load media relationship to load images of food
            }

            DB::commit();
        } catch (ValidatorException $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($food->toArray(), __('lang.saved_successfully', ['operator' => __('lang.food')]));
    }

    /**
     * Update the specified Food in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $input = $this->validateData(true);

        Food::select('id')->whereIn('restaurant_id', $this->getRestaurantIds())->findOrFail($id); // check if id exits

        try {
            DB::beginTransaction();

            $food = $this->foodRepository->update($input, $id);
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->foodRepository->model());

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $food->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }

            if (isset($input['image']) && $input['image']) {
                if ($food->hasMedia('image')) { // delete old image
                    $food->getFirstMedia('image')->delete();
                }

                upload_image($request->image, $food->id, 'image')
                    ->getMedia('image')
                    ->first()
                    ->copy($food, 'image');

                $food->load('media'); // load media relationship to load images of food
            }
            $food->setHidden(['restaurant']); // skip load restaurant relationship
            $food->load('category');

            DB::commit();
        } catch (ValidatorException $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($food->toArray(), __('lang.updated_successfully', ['operator' => __('lang.food')]));
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

        $nullable_on_update = $update ?   'nullable' : 'required';
        $rules = [
            'name' => "$nullable_on_update|string|min:3|max:64",
            'price' => "$nullable_on_update|numeric|min:0",
            'discount_price' => "$nullable_on_update|numeric|min:0",
            'description' => "$nullable_on_update|string|min:3|max:1000",
            'ingredients' => 'nullable|string|max:1000',
            'package_items_count' => 'nullable|numeric|min:1',
            'weight' => "$nullable_on_update|string|min:3|max:64",
            'unit' => 'nullable|string|min:1|max:10',
            'image' => "$nullable_on_update|mimes:jpeg,png,jpg,gif,svg|max:25600",
            'featured' => "$nullable_on_update",
            'deliverable' => "$nullable_on_update",
            'available' => "$nullable_on_update",
            'category_id' => "$nullable_on_update|integer|exists:categories,id",
            'restaurant_id' => 'nullable|in_array:' . implode(',', $this->getRestaurantIds())
        ];


        $input = $request->validate($rules);
        $input = array_merge($input, [
            'package_items_count' => $request->get('package_items_count', 1),
            'unit' => $request->get('unit', 'g'),
            'restaurant_id' => $request->get('restaurant_id', $this->getRestaurantIds()[0])
        ]);
        return $input;
    }
}
