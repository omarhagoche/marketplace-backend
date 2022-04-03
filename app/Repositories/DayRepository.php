<?php

namespace App\Repositories;

use App\Models\Day;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DayRepository
 * @package App\Repositories
 * @version March 21, 2022, 10:42 am EET
 *
 * @method Day findWithoutFail($id, $columns = ['*'])
 * @method Day find($id, $columns = ['*'])
 * @method Day first($columns = ['*'])
*/
class DayRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Day::class;
    }
}
