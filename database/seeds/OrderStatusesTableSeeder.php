<?php

use Illuminate\Database\Seeder;

class OrderStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('order_statuses')->delete();

        \DB::table('order_statuses')->insert([
            [
                'id' => 1,
                'status' => 'Order Received',
                'created_at' => '2019-08-30 16:39:28',
                'updated_at' => '2019-10-15 18:03:14',
            ],
            [
                'id' => 2,
                'status' => 'Preparing',
                'created_at' => '2019-10-15 18:03:50',
                'updated_at' => '2019-10-15 18:03:50',
            ],
            [
                'id' => 3,
                'status' => 'Ready',
                'created_at' => '2019-10-15 18:04:30',
                'updated_at' => '2019-10-15 18:04:30',
            ],
            [
                'id' => 4,
                'status' => 'On the Way',
                'created_at' => '2019-10-15 18:04:13',
                'updated_at' => '2019-10-15 18:04:13',
            ],
            [
                'id' => 5,
                'status' => 'Delivered',
                'created_at' => '2019-10-15 18:04:30',
                'updated_at' => '2019-10-15 18:04:30',
            ],
            [
                'id' => 6,
                'status' => 'no drivers available',
                'created_at' => '2019-10-15 18:04:30',
                'updated_at' => '2019-10-15 18:04:30',
            ],
            [
                'id' => 7,
                'status' => 'waiting for drivers',
                'created_at' => '2019-10-15 18:04:30',
                'updated_at' => '2019-10-15 18:04:30',
            ],
            [
                'id' => 8,
                'status' => 'waiting for restaurant',
                'created_at' => '2019-10-15 18:04:30',
                'updated_at' => '2019-10-15 18:04:30',
            ],
        ]);
    }
}
