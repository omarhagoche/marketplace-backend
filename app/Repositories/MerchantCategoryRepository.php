<?php

namespace App\Repositories;

use App\Models\MerchantCategory;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MerchantCategoryRepository
 * @package App\Repositories
 * @version April 4, 2022, 11:08 pm EET
 *
 * @method MerchantCategory findWithoutFail($id, $columns = ['*'])
 * @method MerchantCategory find($id, $columns = ['*'])
 * @method MerchantCategory first($columns = ['*'])
*/
class MerchantCategoryRepository extends BaseRepository
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
        return MerchantCategory::class;
    }
}
