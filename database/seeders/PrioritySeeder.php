<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Priority;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        Priority::create([
            'name'=>'Low',
            'color'=>'green'
        ]);

        Priority::create([
            'name'=>'Medium',
            'color'=>'yellow'
        ]);

        Priority::create([
            'name'=>'High',
            'color'=>'red'
        ]);
    }
}