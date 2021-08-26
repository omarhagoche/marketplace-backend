<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;
use Log;

class CloseUnassignedOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Id of close unassigend orders operation , to use it in tracking in log system
     */
    protected $operationId;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->operationId = strtoupper(uniqid());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("Started CloseUnassignedOrders Job : id => #$this->operationId");
        $drivers =  $this->cancelOrdersThatNotAssignedToDrivers()->count();
        $restaurants =  $this->cancelOrdersThatNotAcceptedFromRestaurant()->count();
        \Log::info("Ended CloseUnassignedOrders Job : id => #$this->operationId | drivers: $drivers | restaurants : $restaurants");
    }

    /**
     * Cancel orders that took long time and did not assigned to drivers yet
     * 
     * @return App\Models\Order
     */
    public function cancelOrdersThatNotAssignedToDrivers()
    {
        $orders = Order::whereNull('driver_id')
            ->where('order_status_id', 10)
            ->where('created_at', '<', now()->addSeconds(-300))
            ->get();

        foreach ($orders as $order) {
            $order->order_status_id = 100; // canceled_no_drivers_available
            $order->save();
            $this->log($order);
        }
        return $orders;
    }

    /**
     * Cancel order that took long time and restaurant did not accept them
     * 
     * @return App\Models\Order
     */
    public function cancelOrdersThatNotAcceptedFromRestaurant()
    {
        $orders = Order::where('order_status_id', 20)
            ->where('created_at', '<', now()->addSeconds(-360))
            ->get();

        foreach ($orders as $order) {
            $order->order_status_id = 105; // canceled_restaurant_did_not_accept
            $order->save();
            $this->log($order);
        }
        return $orders;
    }

    /**
     * Write information about canceled orders to log file
     * 
     * @param App\Models\Order $order
     * 
     * @return void
     */
    protected function log($order)
    {
        $data = $order->only('id', 'order_status_id', 'created_at', 'updated_at');
        $data['old_order_status_id'] = $order->getOriginal('order_status_id');
        $data['operation_id'] = $this->operationId;
        Log::channel('canceledOrders')->log($data);
    }
}
