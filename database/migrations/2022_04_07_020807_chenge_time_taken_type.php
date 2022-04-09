<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChengeTimeTakenType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('foods', 'time_taken')){

            Schema::table('foods', function (Blueprint $table) {

                $table->dropColumn('time_taken');

            });

        }
        Schema::table('foods', function (Blueprint $table) {
            $table->string("time_taken")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food', function (Blueprint $table) {
            //
        });
    }
}
