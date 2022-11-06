<?php

namespace Database\Seeders;

use App\Models\TableStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TableStatus::create([
            'name' => 'Pending'
        ]);
        TableStatus::create([
            'name' => 'Available'
        ]);
        TableStatus::create([
            'name' => 'Unavailable'
        ]);
    }
}
