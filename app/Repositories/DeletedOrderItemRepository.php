<?php

namespace App\Repositories;

use App\Models\DeletedOrderItem;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CartRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method Cart findWithoutFail($id, $columns = ['*'])
 * @method Cart find($id, $columns = ['*'])
 * @method Cart first($columns = ['*'])
*/
class DeletedOrderItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'food_id',
        'user_id',
        'quantity',
        'price'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeletedOrderItem::class;
    }
}
