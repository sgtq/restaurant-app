<?php

namespace Database\Seeders;

use App\Models\TableLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TableLocation::create([
            'name' => 'Front'
        ]);
        TableLocation::create([
            'name' => 'Inside'
        ]);
        TableLocation::create([
            'name' => 'Outside'
        ]);
    }
}
