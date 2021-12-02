<?php

/**
 * File name: Coupon.php
 * Last modified: 2020.08.23 at 19:56:12
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Models;

use Eloquent as Model;

/**
 * Class Coupon
 * @package App\Models
 * @version August 23, 2020, 6:10 pm UTC
 *
 * @property string code
 * @property double discount
 * @property string discount_type
 * @property string description
 * @property dateTime expires_at
 * @property boolean enabled
 * @property boolean on_delivery_fee
 */
class Coupon extends Model
{

    public $table = 'coupons';



    public $fillable = [
        'code',
        'discount',
        'discount_type',
        'description',
        'expires_at',
        'enabled',
        'on_delivery_fee',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string',
        'discount' => 'double',
        'discount_type' => 'string',
        'description' => 'string',
        'expires_at' => 'datetime',
        'enabled' => 'boolean',
        'on_delivery_fee' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required|unique:coupons|max:50',
        'discount' => 'required|numeric|min:0',
        'discount_type' => 'required',
        'expires_at' => 'required|date|after_or_equal:tomorrow'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'restaurant_id'
    ];


    /**
     * get Restaurant id attribute
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function getRestaurantIdAttribute()
    {
        $r = $this->discountables->where("discountable_type", Restaurant::class)->first();
        if ($r) {
            return $r->discountable_id;
        }
        return null;
    }


    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    public function discountables()
    {
        return $this->hasMany(\App\Models\Discountable::class, 'coupon_id');
    }
}
