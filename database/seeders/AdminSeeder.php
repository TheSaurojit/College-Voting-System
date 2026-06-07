<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the default admin user.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@college.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@college.com',
                'password' => Hash::make('password'),
                'phone' => '9999999999',
                'role' => 'admin',
            ]
        );
    }
}
