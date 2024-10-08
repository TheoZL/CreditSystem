<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class CantonSeeder  extends CsvSeeder
{

    public function __construct()
    {
        $this->file = '/database/seeders/csv/cantons.csv';
        $this->tablename = 'cantons';
        $this->aliases = ['Canton' => 'name', 'Province' => 'province_id'];
        $this->delimiter = ',';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        parent::run();
    }
}
