<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Agent',
            'email' => 'agent@test.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@test.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);
    }
}