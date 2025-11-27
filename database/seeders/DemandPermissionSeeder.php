<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DemandPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง permissions สำหรับ demands
        $permissions = [
            'view demand',
            'create demand',
            'update demand',
            'delete demand',
            'print demand',
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
                'view demand',
                'create demand',
                'update demand',
                'print demand',
            ]);
        }

        // กำหนด permissions ให้ role user (ถ้ามี) - เฉพาะดูและพิมพ์
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->givePermissionTo(['view demand', 'print demand']);
        }

        $this->command->info('Demand permissions created successfully!');
    }
}
