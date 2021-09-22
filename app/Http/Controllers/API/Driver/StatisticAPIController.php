<?php

/**
 * File name: StatisticAPIController.php
 * Last modified: 2021.09.22 at 17:25:21
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Models\SettlementDriver;
use App\Models\Order;
use Illuminate\Http\Request;
use DB;

class StatisticAPIController extends Controller
{

    public function index(Request $request)
    {
        $user_id = auth()->user()->id;
        //$delivered_orders = Order::where('driver_id', $user_id)->where('order_status_id', 80)->count();
        $settlements = SettlementDriver::select(DB::raw("IFNULL(SUM(amount),0) amount"), DB::raw('IFNULL(SUM(count),0) count'))
            ->where('driver_id', $user_id)->first()->toArray();

        $availabel_orders_for_settlement = Order::select(DB::raw("IFNULL(SUM(delivery_fee),0) amount"), DB::raw('IFNULL(COUNT(*),0) count'))
            ->where('driver_id', $user_id)
            ->where('order_status_id', 80) // Order Delivered
            ->whereNull('settlement_driver_id')
            ->first();

        return [
            //'delivered_orders' => $delivered_orders,
            'settlements' => $settlements,
            'availabel_orders_for_settlement' => $availabel_orders_for_settlement,
        ];
    }
}
