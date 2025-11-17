<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Coffee Kane',
            'email' => 'admin@coffeekane.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Coffee Kane',
            'email' => 'kasir@coffeekane.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);

        User::create([
            'name' => 'Customer Contoh',
            'email' => 'customer@coffeekane.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}
