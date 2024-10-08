<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(UserSeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(CantonSeeder::class);
        $this->call(DistritSeeder::class);
        $this->call(BillingScheduleSeeder::class);
        $this->call(PaymentTypesSeeder::class);
        $this->call(CreditStatusSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
