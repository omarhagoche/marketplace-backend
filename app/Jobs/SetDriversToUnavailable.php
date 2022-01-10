<?php

namespace App\Jobs;

use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetDriversToUnavailable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $firestore = app('firebase.firestore')->getFirestore();

        // get drivers from firestore
        $db_drivers = $firestore
            ->collection('drivers')
            ->orderBy("last_access", "desc")
            ->where('working_on_order', '=', false)
            ->where('available', '=', true)
            // We multiply in 1000 to get timestamp with 13 digits (in microseconds)
            ->where('last_access', '<', now()->addSeconds(setting('drivers_to_unavailable_last_access_time', 36000) * -1)->timestamp * 1000)
            ->documents();


        // start batch upload data to firestore
        $batch = $firestore->batch();
        $drivers = collect(); // to save data of drivers to make update query on database also to save data to logger 

        foreach ($db_drivers as $d) {
            $driver = $d->data();
            $drivers->push($driver);
            $driver['available'] = false;
            // set document in batch
            $batch->set($firestore->collection("drivers")->document($driver['id']), $driver);
        }


        DB::transaction(function () use ($drivers, $batch) {
            Driver::whereIn('user_id', $drivers->only('id'))->update(['available' => False]);
            $batch->commit(); // upload or commit batch
            // end batch upload data to firestore
            Log::channel('unavailableDrivers')->info([
                'count' => $drivers->count(),
                'drivers' => $drivers->toArray(),
            ]); // save data to logger about operation
        });
    }
}
