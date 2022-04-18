<?php

namespace App\Repositories;

use App\Models\ProductInfo;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProductInfoRepository
 * @package App\Repositories
 * @version April 5, 2022, 1:30 am EET
 *
 * @method ProductInfo findWithoutFail($id, $columns = ['*'])
 * @method ProductInfo find($id, $columns = ['*'])
 * @method ProductInfo first($columns = ['*'])
*/
class ProductInfoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cost',
        'expiry_date',
        'barcode',
        'food_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductInfo::class;
    }
}
