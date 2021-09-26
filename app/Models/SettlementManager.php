<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class SettlementManager
 * @package App\Models
 * @version September 25, 2021, 1:46 am UTC
 *
 * @property integer creator_id
 * @property integer restaurant_id
 * @property integer count
 * @property decimal amount
 * @property decimal fee
 * @property string note
 */
class SettlementManager extends Model
{

    public $table = 'settlement_managers';



    public $fillable = [
        'creator_id',
        'restaurant_id',
        'count',
        'amount',
        'fee',
        'note',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'creator_id' => 'integer',
        'restaurant_id' => 'integer',
        'count' => 'integer',
        'amount' => 'float',
        'fee' => 'float',
        'note' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'restaurant_id' => 'required|integer|exists:restaurants,id',
        'note' => 'nullable|string',
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function restaurant()
    {
        return $this->belongsTo(\App\Models\Restaurant::class, 'restaurant_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id', 'id');
    }

    /**
     * Get all of the orders for the SettlementDriver
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function loadOrders()
    {
        if ($this->relationLoaded('orders')) return;
        $this->load('orders', 'orders.payment', 'orders.foodOrders');
        $this->orders->map(function ($o) {
            $o->amount =  $o->foodOrders->sum(function ($f) {
                return $f->quantity * $f->price;
            });
            $o->fee = round(($this->fee / 100) * $o->amount, 3);
        });
    }
}
