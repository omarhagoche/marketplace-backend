<?php

namespace App\Jobs;

use Carbon\Carbon;
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
     * Id of close unassigend orders operation , to use it in tracking in log system
     */
    protected $operationId;


    /**
     * @var
     */
    protected $from_time;

    /**
     * @var
     */
    protected $to_time;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->operationId = strtoupper(uniqid());
        $this->from_time = now()->addMinutes(-5)->format("H:i"); // to update restaurants that should be close or open before 5 minutes
        $this->to_time =  now()->addMinutes(2)->format("H:i"); // to update restaurants that should be close or open after 2 minutes
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::channel('openAndCloseRestaurant')->info("Start OpenAndCloseRestaurant Job: id => #$this->operationId");
        try {
            DB::transaction(function () {

                $restaurants_will_open = $this->getRestaurants(true);
                $restaurants_will_close = $this->getRestaurants(false);
                $count_affected_open = 0;
                $count_affected_close = 0;

                if (count($restaurants_will_open)) {
                    $count_affected_open = $this->updateRestaurantsStatus(true,$restaurants_will_open);
                }
                if (count($restaurants_will_close)) {
                    $count_affected_close =  $this->updateRestaurantsStatus(false,$restaurants_will_close);
                }

                // log info about operation to use it when tacking something
                Log::channel('openAndCloseRestaurant')->info([
                    'from_time' => $this->from_time,
                    'to_time' => $this->to_time,
                    'count_opened_restaurants' => $restaurants_will_open->count(),
                    'count_affected_open' => $count_affected_open,
                    'count_closed_restaurants' => $restaurants_will_close->count(),
                    'count_affected_close' => $count_affected_close,
                    'opened_restaurants' => $restaurants_will_open->toArray(),
                    'closed_restaurants' => $restaurants_will_close->toArray(),
                ]);

                Log::channel('openAndCloseRestaurant')->info("End OpenAndCloseRestaurant Job: id => #$this->operationId");
            });
        } catch (\Throwable $th) {
            Log::channel('openAndCloseRestaurantErrors')->error("Error OpenAndCloseRestaurant Job: id => #$this->operationId \n Error:$th");
        }
    }


    /**
     * Get restuarnts who needs to proccess
     * @param bool $open load restaurants that should be open or not
     * 
     * @return Collection
     */
    protected function getRestaurants($open)
    {
        $columnForDay = 'day_restaurants.close_at';
        $column = 'close_at';

        if ($open) {
            $columnForDay = 'day_restaurants.open_at';
            $column = 'open_at';
        }
        $dayName=Carbon::now()->englishDayOfWeek;
        $idsRestaurantsFromDay=DB::table('restaurants')
                    ->join('day_restaurants', 'restaurants.id', '=', 'day_restaurants.restaurant_id')
                    ->join('days', 'days.id', '=', 'day_restaurants.day_id')
                    ->where('days.name','like', "%$dayName%")
                    ->where('restaurants.closed',$open)
                    ->whereBetween($columnForDay, [$this->from_time, $this->to_time])
                    ->groupBy('restaurant_id')->pluck('restaurant_id')->toArray();
        $idsRestaurants= DB::table('restaurants')
                ->whereNotIn('restaurants.id', $idsRestaurantsFromDay)
                ->select('restaurants.id')
                ->where('restaurants.closed',$open)
                ->whereBetween($column, [$this->from_time, $this->to_time])
                ->groupBy('id')->pluck('id')->toArray();
    
        $ids=array_merge($idsRestaurants,$idsRestaurantsFromDay);
        return $ids;
    }

    /**
     * Update status of restaurant to open or close
     * @param bool $open update status to open or not
     * 
     * @return int number of rows
     */
    protected function updateRestaurantsStatus($open,$ids=[])
    {
        $column = 'close_at';
        if ($open) {
            $column = 'open_at';
        }

        // we use DB:table instead of Restaurant to skip load appends data like : media and custom_fields 
        return DB::table('restaurants')
                ->whereIn('id',$ids)
                ->where('restaurants.closed',$open)
                ->update(['closed' => !$open]);
    }
}
