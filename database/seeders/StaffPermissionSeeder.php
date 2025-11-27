<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions for staff management
        $permissions = [
            'view staff',
            'create staff', 
            'update staff',
            'delete staff'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // Assign view and create permissions to manager role if exists
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerRole->givePermissionTo(['view staff', 'create staff', 'update staff']);
        }

        echo "Staff permissions created and assigned successfully!\n";
    }
}