<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class SettlementDriver
 * @package App\Models
 * @version September 17, 2021, 9:25 pm UTC
 *
 * @property integer creator_id
 * @property integer driver_id
 * @property decimal amount
 * @property string note
 * @property integer count
 */
class SettlementDriver extends Model
{

    public $table = 'settlement_drivers';


    public $fillable = [
        'creator_id',
        'driver_id',
        'fee',
        'amount',
        'note',
        'count',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'creator_id' => 'integer',
        'driver_id' => 'integer',
        'fee' => 'float',
        'amount' => 'float',
        'note' => 'string',
        'count' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'driver_id' => 'required|integer|exists:users,id',
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
    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id', 'id');
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
}
