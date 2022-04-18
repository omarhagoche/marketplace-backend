<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodOrderExtra extends Model
{
    public $table = 'food_order_extras';
    public $timestamps = false;
    protected $primaryKey = ['food_order_id', 'extra_id'];
    public $incrementing = false;
    
    public $fillable = [
        'food_order_id',
        'extra_id',
        'price'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'food_order_id' => 'integer',
        'extra_id' => 'integer',
        'price' => 'double',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'price' => 'required',
    ];


    public function foodOrder()
    {
        return $this->belongsTo(\App\Models\FoodOrder::class, 'food_order_id', 'id');
    }
    public function extra()
    {
        return $this->belongsTo(\App\Models\Extra::class, 'extra_id', 'id');
    }
}
