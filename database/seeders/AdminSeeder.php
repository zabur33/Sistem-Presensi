<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat admin account
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@presensi.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Opsional: Membuat admin tambahan
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@presensi.com',
            'password' => Hash::make('superadmin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
