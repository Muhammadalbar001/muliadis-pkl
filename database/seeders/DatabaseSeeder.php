<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Super Admin (Pemegang Aplikasi / IT)
        // Memiliki akses ke Manajemen User & Semua Laporan
        User::create([
            'name' => 'IT Super Admin',
            'username' => 'superadmin',
            'email' => 'it.admin@muliadis.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Akun Pimpinan (Direktur / Owner)
        // Memiliki akses ke Laporan Eksekutif & Dashboard
        User::create([
            'name' => 'Bapak Direktur',
            'username' => 'pimpinan',
            'email' => 'direktur@muliadis.com',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);

        // 3. Akun Supervisor (Kepala Operasional / Gudang)
        // Memiliki akses ke Operasional & Laporan Analisa
        User::create([
            'name' => 'Supervisor Ops',
            'username' => 'supervisor',
            'email' => 'spv@muliadis.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        // 4. Akun Admin (Staff Input / Kasir)
        // Fokus pada Input Transaksi & Data Master Dasar
        User::create([
            'name' => 'Staff Admin Kasir',
            'username' => 'admin',
            'email' => 'admin.staff@muliadis.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}