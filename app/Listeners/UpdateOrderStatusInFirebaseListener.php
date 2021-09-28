<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOrderStatusInFirebaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;
        if (!$order->wasChanged('order_status_id')) return;

        app('firebase.firestore')
            ->getFirestore()
            ->collection('current_orders')
            ->document($order->id)
            ->set([
                'id' => $order->id,
                'order_status_id' => $order->order_status_id,
                'driver' => $order->driver_id,
                'created_at' => $order->created_at->timestamp,
            ]);
    }
}
