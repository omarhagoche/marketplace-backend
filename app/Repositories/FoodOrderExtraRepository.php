<?php

namespace App\Repositories;

use App\Models\FoodOrderExtra;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class FoodOrderRepository
 * @package App\Repositories
 * @version August 31, 2019, 11:18 am UTC
 *
 * @method FoodOrder findWithoutFail($id, $columns = ['*'])
 * @method FoodOrder find($id, $columns = ['*'])
 * @method FoodOrder first($columns = ['*'])
*/
class FoodOrderExtraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'food_order_id',
        'extra_id',
        'price',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FoodOrderExtra::class;
    }
}
