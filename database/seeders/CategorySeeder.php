<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Bug',
            'Technical Support',
            'Billing',
            'Feature Request',
            'Account',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'description' => $category . ' category'
            ]);
        }
    }
}