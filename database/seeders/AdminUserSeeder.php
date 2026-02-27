<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update Admin User
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '1234567890',
                'address' => 'Admin Office, City Center'
            ]
        );
        $this->command->info('Admin user created/updated successfully.');

        // Create or update Staff User
        User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '0987654321',
                'address' => 'Staff Office'
            ]
        );
        $this->command->info('Staff user created/updated successfully.');

        // Create or update Citizen User
        User::updateOrCreate(
            ['email' => 'citizen@example.com'],
            [
                'name' => 'Citizen User',
                'password' => Hash::make('password'),
                'role' => 'citizen',
                'phone' => '5555555555',
                'address' => '123 Main St, City'
            ]
        );
        $this->command->info('Citizen user created/updated successfully.');
    }
}