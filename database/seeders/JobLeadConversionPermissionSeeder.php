<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class JobLeadConversionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permission for job lead conversion
        $permission = Permission::firstOrCreate(['name' => 'job-lead-convert']);

        // Assign to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permission);
        }

        // Assign to manager role
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerRole->givePermissionTo($permission);
        }

        // Assign to recruiter role if exists
        $recruiterRole = Role::where('name', 'recruiter')->first();
        if ($recruiterRole) {
            $recruiterRole->givePermissionTo($permission);
        }
    }
}
