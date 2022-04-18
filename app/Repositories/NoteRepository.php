<?php

namespace App\Repositories;

use App\Models\Note;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version August 31, 2019, 11:11 am UTC
 *
 * @method Order findWithoutFail($id, $columns = ['*'])
 * @method Order find($id, $columns = ['*'])
 * @method Order first($columns = ['*'])
 */
class NoteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'from_user_id',
        'to_user_id',
        'text'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Note::class;
    }
}
