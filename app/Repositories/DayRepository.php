<?php

namespace App\Repositories;

use App\Models\Day;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DayRepository
 * @package App\Repositories
 * @version April 14, 2022, 4:31 pm EET
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
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Day::class;
    }
}
