<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin Name',  // Add the name of the admin
            'email' => 'admin@example.com',  // Admin email
            'password' => bcrypt('adminpassword'), // Admin password
        ]);
    }
}
