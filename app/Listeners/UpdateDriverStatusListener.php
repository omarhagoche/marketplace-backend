<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Driver;

class UpdateDriverStatusListener
{

    private $order;

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

        // exit if order not new and dirver_id not changed , so that menas you do not do anything
        if (!$this->order->wasRecentlyCreated && !$this->order->wasChanged('driver_id')) return;

        $old_driver_id = $this->order->getChanges()['driver_id'] ?? false;

        if (($this->order->wasRecentlyCreated || !$old_driver_id) &&  $this->order->driver_id) { //  assigned order to driver
            $this->driverAssigned();
            return;
        }


        if ($old_driver_id &&  !$this->order->driver_id) { // driver canceled from order
            $this->driverCanceled();
            return;
        }

        $this->driverChanged(); // otherwise driver changed
    }

    /**
     * Update driver working_on_order status to work (true)
     */
    protected function driverAssigned()
    {
        $this->updateDriverStatus($this->order->driver_id, true);
    }


    /**
     * Update driver working_on_order status
     * Set old driver as free , and set new driver to busy
     */
    protected function driverChanged()
    {
        $this->updateDriverStatus($this->order->getOriginal('driver_id'), false);
        $this->updateDriverStatus($this->order->driver_id, true);
    }


    /**
     * Set driver as free
     */
    protected function driverCanceled()
    {
        $this->updateDriverStatus($this->order->driver_id, false);
    }

    /**
     * Update driver working_on_order status in firebase
     * 
     * @param int $driver_id
     * @param boolean $working_on_order
     * 
     * @return void
     */
    private function updateDriverStatus($driver_id, $working_on_order)
    {
        $driver = Driver::select('id', 'available')->where('user_id', $driver_id)->first();

        $firestore = app('firebase.firestore')->getFirestore();
        $ref = $firestore->collection('drivers')->document($driver->id);

        $data = $ref->snapshot()->data();

        if ($data) { // update data if exists
            $data['working_on_order'] = $working_on_order;
        } else { // set data if not exists
            $data = [
                'id' => $driver->id,
                'available' => $driver->available,
                'working_on_order' => $working_on_order,
                'latitude' => 0,
                'longitude' => 0,
                'last_access' => null,
            ];
        }

        $ref->set($data);
    }
}
