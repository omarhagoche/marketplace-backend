<?php
/**
 * File name: AdvertisementCompany.php
 * Last modified: 2022.05.12
 */

namespace App\Models;

use Eloquent as Model;

/**
 * Class AdvertisementCompany
 * @package App\Models
 * @version May 12, 2022
 *
 * @property string name
 * @property string link
 * @property string logo
 * @property integer manager_user_id
 */
class AdvertisementCompany extends Model
{

    public $table = 'advertisement_company';
    


    public $fillable = [
        'name',
        'link',
        'logo',
        'manager_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'link' => 'string',
        'logo' => 'string',
        'manager_user_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'link' => 'required',
        'logo' => 'required',
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

}
