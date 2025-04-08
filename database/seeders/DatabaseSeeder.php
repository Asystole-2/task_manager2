<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $roles = ['admin', 'manager', 'member'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Assign admin role to first user
        User::first()->assignRole('admin');
    }
}
