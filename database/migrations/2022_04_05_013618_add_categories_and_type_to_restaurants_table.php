<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoriesAndTypeToRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            /**
             * Merchant type is used to differentiate restaurants from other merchants type like:
             * Supermarkets
             * Pharmacies 
             * ..Etc 
             */
            $table->enum('merchant_type', ['RESTAURANT', 'SUPERMARKET','SHOP'])->default('RESTAURANT');	
            $table->integer('merchant_category_id')->unsigned()->nullable();
            $table->foreign('merchant_category_id')->references('id')->on('merchant_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('merchant_type');
            $table->dropColumn('merchant_category_id');
        });
    }
}
