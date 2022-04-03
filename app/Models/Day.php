<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Day
 * @package App\Models
 * @version March 21, 2022, 10:42 am EET
 *
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
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    /**
     * The restaurants that belong to the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'day_restaurants', 'day_id', 'restaurant_id');
    }
    
    
}
