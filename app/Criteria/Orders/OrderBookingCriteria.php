<?php

namespace App\Criteria\Orders;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OrderBookingCriteria.
 *
 * @package namespace App\Criteria\Orders;
 */
class OrderBookingCriteria implements CriteriaInterface
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
        return $model
        ->where('restaurant_id',auth()->user()->restaurants->first()->id)
        ->where('delivery_datetime','!=',null)
        ;
    }
}
