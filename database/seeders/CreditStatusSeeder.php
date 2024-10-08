<?php

namespace Database\Seeders;

use App\Models\CreditStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CreditStatus::create([
            'name' => 'Activo',
        ]);

        CreditStatus::create([
            'name' => 'Cancelado',
        ]);

        CreditStatus::create([
            'name' => 'En Atrazo',
        ]);
    }
}
