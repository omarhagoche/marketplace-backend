<?php

namespace App\Repositories;

use App\Models\Restaurant;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class RestaurantRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Restaurant findWithoutFail($id, $columns = ['*'])
 * @method Restaurant find($id, $columns = ['*'])
 * @method Restaurant first($columns = ['*'])
 */
class RestaurantRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'phone',
        'mobile',
        'information',
        'delivery_fee',
        'default_tax',
        'delivery_range',
        'available_for_delivery',
        'active',
        'featured',
        'admin_commission',
        'featured',
        'is_restaurant',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Restaurant::class;
    }

   
    /**
     * Get supermarket and its products and product infos
     */
    public function supermarketWithProducts($id){

        return Restaurant::whereId($id)->with('foods.productInfo')->first();
    }

     /**
     * get my restaurants
     */
    public function myRestaurants()
    {
        return Restaurant::join("user_restaurants", "restaurant_id", "=", "restaurants.id")
            ->where('user_restaurants.user_id', auth()->id())->get();
    }

    public function myActiveRestaurants()
    {
        return Restaurant::join("user_restaurants", "restaurant_id", "=", "restaurants.id")
            ->where('user_restaurants.user_id', auth()->id())
            ->where('restaurants.active', '=', '1')->get();
    }
}
