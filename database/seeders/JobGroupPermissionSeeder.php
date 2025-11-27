<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class JobGroupPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง permissions สำหรับ job groups
        $permissions = [
            'view job-group',
            'create job-group',
            'update job-group',
            'delete job-group',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // กำหนด permissions ให้ role admin (ถ้ามี)
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // กำหนด permissions ให้ role manager (ถ้ามี)
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerRole->givePermissionTo([
                'view job-group',
                'create job-group',
                'update job-group',
            ]);
        }

        // กำหนด permissions ให้ role user (ถ้ามี) - เฉพาะดูอย่างเดียว
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->givePermissionTo('view job-group');
        }

        $this->command->info('Job Group permissions created successfully!');
    }
}
