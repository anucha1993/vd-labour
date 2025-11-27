<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffSubPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions for staff-sub management
        $permissions = [
            'view staff-sub',
            'create staff-sub', 
            'update staff-sub',
            'delete staff-sub'
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
            $managerRole->givePermissionTo(['view staff-sub', 'create staff-sub', 'update staff-sub']);
        }

        echo "Staff Sub permissions created and assigned successfully!\n";
    }
}