<?php

namespace Database\Seeders;

use App\Models\BillingSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillingScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BillingSchedule::create([
            'option' => 'Semanal',
        ]);

        BillingSchedule::create([
            'option' => 'Quincenal',
        ]);

        BillingSchedule::create([
            'option' => 'Mensual',
        ]);
    }
}
