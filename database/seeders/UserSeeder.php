<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data users yang akan dibuat
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
            ]
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']], // Key untuk mencari data yang sudah ada
                $userData // Data yang akan di-update atau di-create
            );
        }
    }
}