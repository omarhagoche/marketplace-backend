<?php

namespace App\Criteria\Restaurants;

use App\Enums\MerchantType;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SupermarketCriteria.
 *
 * @package namespace App\Criteria\Restaurants;
 */
class SupermarketCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->while('restaurants.merchant_type', MerchantType::SUPERMARKET);
    }
}
