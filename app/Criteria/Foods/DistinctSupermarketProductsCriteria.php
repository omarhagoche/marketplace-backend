<?php
/**
 * File name: FoodsOfCategoriesCriteria.php
 * Last modified: 2020.08.02 at 17:31:59
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */


namespace App\Criteria\Foods;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class DistinctSupermarketProductsCriteria.
 *
 * @package namespace App\Criteria\Foods;
 */
class DistinctSupermarketProductsCriteria implements CriteriaInterface
{
    

    /**
     * DistinctSupermarketProductsCriteria constructor.
     */
    public function __construct()
    {
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->whereHas('restaurant', function ($query) {
            $query->where('merchant_type', 'like', 'SUPERMARKET');
        }) ->get();
       
    }
}


