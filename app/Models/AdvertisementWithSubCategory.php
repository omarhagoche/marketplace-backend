<?php
/**
 * File name: Advertisement.php
 * Last modified: 2022.05.12
 */

namespace App\Models;

use Eloquent as Model;

/**
 * Class Cart
 * @package App\Models
 * @version May 12, 2022
 *
 * @property \App\Models\AdvertisementSubCategory advertisementSubCategory
 * @property \App\Models\Advertisement advertisement
 * @property integer advertisement_id
 * @property integer sub_category_id
 */
class AdvertisementWithSubCategory extends Model
{

    public $table = 'advertisement_sub_category_join';
    


    public $fillable = [
        'advertisement_id',
        'sub_category_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'advertisement_id' => 'integer',
        'sub_category_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'advertisement_id' => 'required',
        'sub_category_id' => 'required'        
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields'
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


     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function advertisementSubCategory()
    {
        return $this->belongsTo(\App\Models\AdvertisementSubCategory::class, 'sub_category_id', 'id');
    }

       /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function advertisement()
    {
        return $this->belongsTo(\App\Models\Advertisement::class, 'advertisement_id', 'id');
    }


}
