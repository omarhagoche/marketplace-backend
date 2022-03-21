<?php

use Illuminate\Database\Seeder;

class DayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('days')->delete();

            DB::table('days')->insert([
                1=> [
                        'id' => 1,
                        'name' => 'saturday',
                    ],
                2=> [
                        'id' => 2,
                        'name' => 'sunday',
                    ],

                3=> [
                        'id' => 3,
                        'name' => 'Monday ',
                    ],

                4=> [
                        'id' => 4,
                        'name' => 'tuesday',
                    ],

                5=> [
                        'id' => 5,
                        'name' => 'wednesday',
                    ],

                6=> [
                        'id' => 6,
                        'name' => 'thursday',
                    ],

                7=> [
                        'id' => 7,
                        'name' => 'friday',
                    ],
                
                ]
            );

        } catch (Exception $exception) {
        }
    }
}
