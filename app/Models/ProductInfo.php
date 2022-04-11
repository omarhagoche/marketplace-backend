<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ProductInfo
 * @package App\Models
 * @version April 5, 2022, 1:30 am EET
 *
 * @property float cost
 * @property date expiry_date
 * @property string barcode
 * @property bigInt food_id
 */
class ProductInfo extends Model
{

    public $table = 'product_infos';
    


    public $fillable = [
        'cost',
        'expiry_date',
        'barcode',
        'food_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cost' => 'float',
        'expiry_date' => 'date',
        'barcode' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cost' => 'float',
        'expiry_date' => 'date',
        'barcode' => 'string',
        'food_id' => 'required'
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
        $hasCustomField = in_array(static::class,setting('custom_field_models',[]));
        if (!$hasCustomField){
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields','custom_fields.id','=','custom_field_values.custom_field_id')
            ->where('custom_fields.in_table','=',true)
            ->get()->toArray();

        return convertToAssoc($array,'name');
    }

    
    
}
