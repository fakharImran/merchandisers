<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\Company::factory(101)->create();
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@merchandisers.com',
            'password' => bcrypt('admin123'),
        ]);
        // Create admin user
        //create roles and permissions
        $this->call(RolesAndPermissionsSeeder::class);
    }
}
