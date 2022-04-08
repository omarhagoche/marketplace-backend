<?php

/**
 * File name: Food.php
 * Last modified: 2020.06.11 at 16:10:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Models;

use App\Collections\FoodCollection;
use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Food
 * @package App\Models
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @property \App\Models\Restaurant restaurant
 * @property \App\Models\Category category
 * @property \Illuminate\Database\Eloquent\Collection[] discountables
 * @property \Illuminate\Database\Eloquent\Collection Extra
 * @property \Illuminate\Database\Eloquent\Collection Nutrition
 * @property \Illuminate\Database\Eloquent\Collection FoodsReview
 * @property string id
 * @property string name
 * @property double price
 * @property double discount_price
 * @property string description
 * @property string ingredients
 * @property double weight
 * @property boolean featured
 * @property double package_items_count
 * @property string unit
 * @property integer restaurant_id
 * @property integer category_id
 * @property enum type
 * @property timestamp time_taken

 */
class Food extends Model implements HasMedia
{
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    /**
    * @var array
    */
    protected $phoneTypes = [
        'Cellular',
        'Home',
        'Work'
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'price' => 'required|numeric|min:0',
        'restaurant_id' => 'required|exists:restaurants,id',
        'category_id' => 'required|exists:categories,id'
    ];

    public $table = 'foods';
    public $fillable = [
        'name',
        'price',
        'discount_price',
        'description',
        'ingredients',
        'weight',
        'package_items_count',
        'unit',
        'featured',
        'deliverable',
        'available',
        'restaurant_id',
        'category_id',
        'type',
        'time_taken',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'image' => 'string',
        'price' => 'double',
        'discount_price' => 'double',
        'description' => 'string',
        'ingredients' => 'string',
        'weight' => 'double',
        'package_items_count' => 'integer',
        'unit' => 'string',
        'featured' => 'boolean',
        'deliverable' => 'boolean',
        'available' => 'boolean',
        'restaurant_id' => 'integer',
        'category_id' => 'integer',
        'type'=>'enum',
        'time_taken'=>'string',
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media',
        'restaurant',
    ];

    public function getTimeDayAttribute()
    {
        return (strtok($this->time_taken, ':')/24);
    }
    public function getTimeHourAttribute()
    {
        return substr($this->time_taken, strrpos($this->time_taken, ':' )+1);
        return strrchr($this->time_taken,':');
    }
    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
        } else {
            return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
        }
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

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

    /**
     * Add extra_groups to api results 
     * we add it here even user can access it from extras because we changed type of relation between extra and foods , 
     * and we do not want to spend time to make changes on mobile apps and may be we will got bugs (so , it for save time and skip bugs on mobile apps)
     * @return array
     */
    public function getExtraGroupsAttribute()
    {
        return $this->extras->map(function ($item) {
            return $item->extragroup;
        })->unique();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/
    public function extras()
    {
        return $this->belongsToMany(\App\Models\Extra::class, 'extra_foods');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function extraGroups()
    {
        return $this->belongsToMany(\App\Models\ExtraGroup::class, 'extras');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function nutrition()
    {
        return $this->hasMany(\App\Models\Nutrition::class, 'food_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function foodReviews()
    {
        return $this->hasMany(\App\Models\FoodReview::class, 'food_id');
    }

    /**
     * get restaurant attribute
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function getRestaurantAttribute()
    {
        return $this->restaurant()->first(['id', 'name', 'latitude', 'longitude',  'delivery_fee', 'delivery_range', 'delivery_price_type', 'address', 'phone', 'default_tax', 'available_for_delivery']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function restaurant()
    {
        return $this->belongsTo(\App\Models\Restaurant::class, 'restaurant_id', 'id');
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->discount_price > 0 ? $this->discount_price : $this->price;
    }

    /**
     * @return float
     */
    public function applyCoupon($coupon): float
    {
        $price = $this->getPrice();
        if (isset($coupon) && count($this->discountables) + count($this->category->discountables) + count($this->restaurant->discountables) > 0) {
            if ($coupon->discount_type == 'fixed') {
                $price -= $coupon->discount;
            } else {
                $price = $price - ($price * $coupon->discount / 100);
            }
            if ($price < 0) $price = 0;
        }
        return $price;
    }

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }


    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new FoodCollection($models);
    }


    /**
     * Load extra_groups property if extras relation loaded
     * @return static
     */
    public function loadExtraGroupsIfExists()
    {
        if ($this->relationLoaded("extras")) {
            $this->append('extra_groups');
        }
        return $this;
    }

    
    public function extrasFood()
    {
        return $this->hasMany(\App\Models\ExtraFood::class, 'food_id')->orderBy('id','desc');
    }
}
