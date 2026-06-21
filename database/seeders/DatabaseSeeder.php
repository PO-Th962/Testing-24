<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // เพิ่มหรืออัปเดตบัญชีผู้ดูแลระบบ (Admin)
        DB::table('admins')->updateOrInsert(
            ['username' => 'admin'],
            [
                'password' => Hash::make('password'),
                'email' => 'admin@econ.cmu.ac.th',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
