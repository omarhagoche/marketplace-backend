<?php

namespace App\Listeners;

use App\Services\AddOrderToFirebaseService;


class NotifyAvailableOrderListener
{
    protected $order;
    protected $restaurant;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->order = $event->order;
        $this->restaurant = $this->order->foodOrders[0]->food->restaurant;
        if ($this->restaurant->private_drivers || $this->order->payment->isPayOnPickUp()) {
            return;
        }
        new AddOrderToFirebaseService($this->order);
    }
}
