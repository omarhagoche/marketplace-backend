<?php

/**
 * File name: Order.php
 * Last modified: 2020.06.11 at 16:10:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Models;

use Eloquent as Model;
use App\Events\UpdatedOrderEvent;

/**
 * Class Order
 * @package App\Models
 * @version August 31, 2019, 11:11 am UTC
 *
 * @property \App\Models\User user
 * @property \App\Models\DeliveryAddress deliveryAddress
 * @property \App\Models\Payment payment
 * @property \App\Models\OrderStatus orderStatus
 * @property \App\Models\FoodOrder[] foodOrders
 * @property integer user_id
 * @property integer order_status_id
 * @property integer payment_id
 * @property double tax
 * @property double delivery_fee
 * @property string id
 * @property int delivery_address_id
 * @property string hint
 */
class Order extends Model
{

    public $table = 'orders';



    public $fillable = [
        'user_id',
        'unregistered_customer_id',
        'order_status_id',
        'tax',
        'hint',
        'payment_id',
        'delivery_address_id',
        'delivery_fee',
        'active',
        'driver_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'unregistered_customer_id' => 'integer',
        'order_status_id' => 'integer',
        'tax' => 'double',
        'hint' => 'string',
        'status' => 'string',
        'payment_id' => 'integer',
        'delivery_address_id' => 'integer',
        'delivery_fee' => 'double',
        'active' => 'boolean',
        'driver_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'nullable|exists:users,id',
        'unregistered_customer' => 'required_without:user_id',
        'order_status_id' => 'required|exists:order_statuses,id',
        'payment_id' => 'exists:payments,id',
        'driver_id' => 'nullable|exists:users,id',
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'unregistered_customer',
    ];


    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => UpdatedOrderEvent::class,
    ];
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


    /**
     * 
     */
    public static function boot()
    {
        parent::boot();

        /**
         * Event fire before add or update model
         */
        static::saving(function ($model) {
            // set status value depends on order_status_id automatically 
            $model->active = !$model->isStatusCanceled(); // canceled status 
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function unregisteredCustomer()
    {
        return $this->belongsTo(\App\Models\UnregisteredCustomer::class, 'unregistered_customer_id', 'id');
    }


    public function getUnregisteredCustomerAttribute()
    {
        if ($this->unregistered_customer_id) {
            return $this->unregisteredCustomer()->first();
        }
        return null;
    }


    //public function getDeliveryAddressAttribute()
    //{
    //    if ($this->delivery_address_id) {
    //        return $this->deliveryAddress()->first();
    //    }
    //    return null;
    //}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function orderStatus()
    {
        return $this->belongsTo(\App\Models\OrderStatus::class, 'order_status_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function foodOrders()
    {
        return $this->hasMany(\App\Models\FoodOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function foods()
    {
        return $this->belongsToMany(\App\Models\Food::class, 'food_orders');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class, 'payment_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function deliveryAddress()
    {
        return $this->belongsTo(\App\Models\DeliveryAddress::class, 'delivery_address_id', 'id');
    }

    public function isStatusDone()
    {
        return $this->order_status_id == 80; // 80 : delivered
    }

    public function isStatusCanceled()
    {
        return in_array($this->order_status_id, [100, 105, 110, 120, 130, 140]); // canceled 
    }

    public function isStatusWasDone()
    {
        if (!$this->wasChanged('order_status_id')) {
            return false;
        };

        return ($this->getOriginal('order_status_id') ?? false) == 80; // 80 : delivered
    }
}
