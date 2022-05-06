<?php


namespace App\Models;

use Eloquent as Model;
/**
 * Class DeletedOrderItem
 * @version May 1, 2022, 1:05 am UTC 
 * @property integer user_id
 * @property double price
 * @property integer quantity
 * @property integer food_id
 * 
 */
class DeletedOrderItem extends Model
{
    //
    public $table = 'deleted_order_items';

    public $fillable = [
        'user_id',
        'price',
        'quantity',
        'food_id'
    ];

      /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'price' => 'double',
        'quantity' => 'integer',
        'food_id' => 'integer'
    ];

     /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'food_id' => 'required|exists:foods,id',
        'user_id' => 'required|exists:users,id'
    ];


}
