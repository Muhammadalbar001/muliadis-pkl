<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pimpinan (Strategic)
        User::create([
            'name' => 'Pimpinan',
            'username' => 'pimpinan', 
            'email' => 'pimpinan@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'pimpinan'
        ]);

        // 2. Akun Admin (Checker / Tactical - Dulunya Supervisor)
        User::create([
            'name' => 'Admin',
            'username' => 'admin', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // 3. Akun Operator (Feeder / Maker - Dulunya Admin)
        User::create([
            'name' => 'Operator',
            'username' => 'operator', 
            'email' => 'operator@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'operator'
        ]);
    }
}