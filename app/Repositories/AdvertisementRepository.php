<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

use App\Models\Advertisement;

use App\Validators\AdvertisementValidator;

/**
 * Class AdvertisementRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Advertisement findWithoutFail($id, $columns = ['*'])
 * @method Advertisement find($id, $columns = ['*'])
 * @method Advertisement first($columns = ['*'])
 */
class AdvertisementRepository extends BaseRepository
{
 /**
     * @var array
     */
    protected $fieldSearchable = [
        'food_id',
        'user_id',
        'quantity'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Advertisement::class;
    }

    
}
