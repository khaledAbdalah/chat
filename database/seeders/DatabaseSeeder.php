<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678')
        ]);

        User::factory()->create([
            'name' => 'Test User2',
            'email' => 'test2@example.com',
            'password' => Hash::make('12345678')
        ]);

        User::factory()->create([
            'name' => 'Test User3',
            'email' => 'test3@example.com',
            'password' => Hash::make('12345678')
        ]);

        User::factory()->create([
            'name' => 'Test User4',
            'email' => 'test4@example.com',
            'password' => Hash::make('12345678')
        ]);

        User::factory()->create([
            'name' => 'Test User5',
            'email' => 'test5@example.com',
            'password' => Hash::make('12345678')
        ]);

        User::factory()->create([
            'name' => 'Test User6',
            'email' => 'test6@example.com',
            'password' => Hash::make('12345678')
        ]);

    }
}
