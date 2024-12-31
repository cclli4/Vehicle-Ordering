<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Approver 1',
                'username' => 'approver1',
                'email' => 'approver1@example.com',
                'password' => Hash::make('approver123'),
                'role' => 'approver',
                'approval_level' => 1,
            ],
            [
                'name' => 'Approver 2',
                'username' => 'approver2',
                'email' => 'approver2@example.com',
                'password' => Hash::make('approver123'),
                'role' => 'approver',
                'approval_level' => 2,
            ],
            // Tambah driver
            [
                'name' => 'Driver 1',
                'username' => 'driver1',
                'email' => 'driver1@example.com',
                'password' => Hash::make('driver123'),
                'role' => 'driver',
            ],
            [
                'name' => 'Driver 2',
                'username' => 'driver2',
                'email' => 'driver2@example.com',
                'password' => Hash::make('driver123'),
                'role' => 'driver',
            ]
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }
    }
}