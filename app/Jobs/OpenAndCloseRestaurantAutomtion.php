<?php

namespace App\Jobs;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OpenAndCloseRestaurantAutomtion implements ShouldQueue
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
        Log::channel('openAndCloseRestaurant')->info("Started OpenAndCloseRestaurant Job");
        try {
            DB::transaction(function () {
                Restaurant::where('open_at','=',date("H:i"))->update(['closed'=>0]);
                Restaurant::where('close_at','=',date("H:i"))->update(['closed'=>1]);
            });
            Log::channel('openAndCloseRestaurant')->info("close OpenAndCloseRestaurant Job");
        } catch (\Throwable $th) {
            Log::channel('openAndCloseRestaurant')->error("Error OpenAndCloseRestaurant Job, Error:$th");
        }
       
    }
}
