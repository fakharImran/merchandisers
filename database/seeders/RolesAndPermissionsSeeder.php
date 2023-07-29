<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Roles
         Role::create(['name' => 'admin']);
         Role::create(['name' => 'merchandiser']);
         Role::create(['name' => 'manager']);
 
         // Permissions
         Permission::create(['name' => 'mobile']);
         Permission::create(['name' => 'website']);
         Permission::create(['name' => 'admin']);
 
         // Assign permissions to roles
         $adminRole = Role::findByName('admin');
         $adminRole->givePermissionTo(['admin', 'website', 'mobile']);
 
         $merchandiserRole = Role::findByName('merchandiser');
         $merchandiserRole->givePermissionTo('mobile');

         $managerRole = Role::findByName('manager');
         $managerRole->givePermissionTo('website');
    }
}
