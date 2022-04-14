<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Day
 * @package App\Models
 * @version April 14, 2022, 4:31 pm EET
 *
 * @property string name
 */
class Day extends Model
{

    public $table = 'days';
    


    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
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
    /**
     * The days that belong to the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restaurant(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'day_restaurants', 'restaurant_id', 'day_id')->withPivot('open_at','close_at');
    }

    
    
}
