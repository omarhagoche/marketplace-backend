<?php

/**
 * File name: StatisticAPIController.php
 * Last modified: 2021.09.22 at 17:25:21
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API\Manager;

use App\Http\Controllers\Controller;
use App\Models\SettlementManager;
use App\Models\FoodOrder;
use Illuminate\Http\Request;
use DB;

class StatisticAPIController extends Controller
{

    public function index(Request $request)
    {
        $restaurant = auth()->user()->restaurants()->first();

        if (!$restaurant) {
            return response()->json(["error" => "User not linked to any restauarnt"], 403);
        }

        $settlements = SettlementManager::select(
            DB::raw("IFNULL(SUM(amount),0) amount"),
            DB::raw("IFNULL(SUM(amount / fee * 100),0) manager_fee"),
            DB::raw('IFNULL(SUM(count),0) count'),
        )
            ->where('restaurant_id', $restaurant->id)
            ->first()
            ->makeHidden(['custom_fields'])
            ->toArray();

        $settlements['manager_fee'] = round($settlements['manager_fee'], 3);


        $availabel_orders_for_settlement =  FoodOrder::join('foods', 'foods.id', 'food_orders.food_id')
            ->join('orders', 'orders.id', 'food_orders.order_id')
            ->select(
                DB::raw("IFNULL(SUM(food_orders.quantity * food_orders.price),0) manager_fee"),
                DB::raw('IFNULL(COUNT(DISTINCT food_orders.order_id),0) count')
            )
            ->where('foods.restaurant_id', $restaurant->id)
            ->where('orders.order_status_id', 80) // Order Delivered
            ->whereNull('orders.settlement_manager_id')
            ->first();

        $availabel_orders_for_settlement->amount  = round(($restaurant->admin_commission / 100) *  $availabel_orders_for_settlement->manager_fee, 3);

        return [
            'settlements' => $settlements,
            'availabel_orders_for_settlement' => $availabel_orders_for_settlement,
        ];
    }
}
