<?php

use Illuminate\Database\Seeder;
use App\Models\MerchantType;

class MerchantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_type= new MerchantType;
        $merchant_type->name_en ='Restaurant';
        $merchant_type->name_ar='مطعم';
        $merchant_type->save();

        $merchant_type= new MerchantType;
        $merchant_type->name_en ='Supermarket';
        $merchant_type->name_ar='محل';
        $merchant_type->save();
    }
}
