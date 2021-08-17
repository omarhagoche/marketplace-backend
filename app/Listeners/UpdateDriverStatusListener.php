<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Driver;

class UpdateDriverStatusListener
{

    private $order;
    private $driver;

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
        $this->order = $event->order;

        if (($this->order->wasRecentlyCreated && empty($this->order->driver_id)) || !$this->order->wasChanged(['driver_id', 'order_status_id'])) {
            return; // exit if order new without driver , or order driver or status dose not changed
        }

        $this->driver = $this->order->driver->driver ?? false;

        if ($this->order->wasChanged('order_status_id')) {
            if ($this->order->isStatusDone()) {
                $this->setDriverFree();
                return;
            }

            if ($this->order->isStatusWasDone() && !$this->order->wasChanged('driver_id')) {
                $this->setDriverBusy();
                return;
            }
        }

        // exit if order not new and dirver_id not changed , so that menas you do not do anything
        if (!$this->order->wasRecentlyCreated && $this->order->isStatusDone() /* && !$this->order->wasChanged('driver_id') */) return;

        $old_driver_id = $this->order->wasChanged('driver_id') ?  $this->order->getOriginal('driver_id') : false;


        if (($this->order->wasRecentlyCreated || !$old_driver_id) &&  $this->order->driver_id) { //  assigned order to driver
            $this->setDriverBusy();
            return;
        }


        if ($old_driver_id &&  !$this->order->driver_id) { // driver canceled from order
            $this->setDriverFree();
            return;
        }

        $this->driverChanged($old_driver_id); // otherwise driver changed
    }

    /**
     * Update driver working_on_order status to work (true)
     */
    protected function setDriverBusy()
    {
        $this->updateDriverStatus(true);
    }


    /**
     * Update driver working_on_order status
     * Set old driver as free , and set new driver to busy
     * @param int $old_driver_id
     */
    protected function driverChanged($old_driver_id)
    {
        $this->updateDriverStatus(false, Driver::where('user_id', $old_driver_id)->first());
        $this->updateDriverStatus(true);
    }


    /**
     * Set driver as free , that means dirver can see available orders
     */
    protected function setDriverFree()
    {
        $this->updateDriverStatus(false);
    }

    /**
     * Update working_on_order property
     */
    private function updateDriverStatus($working_on_order, $driver = null)
    {
        if (!$driver) {
            $driver = $this->driver;
        }
        $driver->working_on_order = $working_on_order;
        $driver->save();
    }
}
