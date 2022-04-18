<?php

namespace App\Console\Commands;

use App\Models\Day;
use App\Models\Restaurant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetDaysForRestaurant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurants:days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for set days for restaurants with open and close time ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {

            $data=[];
            $restaurants= Restaurant::doesntHave('days')->pluck('id');
            $days=Day::pluck('id');
            if (count($restaurants) !=0) {
                foreach ($restaurants as $key => $restaurantId) {
                    foreach ($days as $key => $dayId) {
                            $data[]=['day_id'=>$dayId,'restaurant_id'=>$restaurantId];
                    }
                }
                DB::table('day_restaurants')->insert($data);
                $this->info('Add day to restaurants was successful! ;)');
            }else {
                $this->info('All  restaurant have  days! :p hahahaha');
            }
                
        });

    }
}
