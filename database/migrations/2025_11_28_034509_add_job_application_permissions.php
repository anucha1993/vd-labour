<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // สร้าง Permissions สำหรับระบบการจัดการใบสมัคร
        $permissions = [
            // Job Management
            'job-list' => 'ดูรายการงาน',
            'job-create' => 'สร้างงานใหม่',
            'job-edit' => 'แก้ไขงาน',
            'job-delete' => 'ลบงาน',
            'job-status-toggle' => 'เปลี่ยนสถานะงาน',
            
            // Job Lead Management
            'job-lead-list' => 'ดูรายการใบสมัคร',
            'job-lead-create' => 'สร้างใบสมัครใหม่',
            'job-lead-edit' => 'แก้ไขใบสมัคร',
            'job-lead-delete' => 'ลบใบสมัคร',
            'job-lead-status-update' => 'อัปเดตสถานะใบสมัคร',
            'job-lead-force-unlock' => 'ปลดล็อคคนงานบังคับ',
            'job-lead-bulk-update' => 'อัปเดตใบสมัครหลายรายการ',
            'job-lead-admin' => 'สิทธิ์ผู้ดูแลระบบใบสมัคร',
            
            // Statistics & Reports
            'job-statistics' => 'ดูสถิติและรายงาน',
            'job-dashboard' => 'ดู Dashboard ระบบใบสมัคร',
        ];

        foreach ($permissions as $name => $description) {
            Permission::create([
                'name' => $name,
                'guard_name' => 'web',
                'description' => $description
            ]);
        }
        
        // กำหนดสิทธิ์ให้ Role ที่มีอยู่แล้ว
        try {
            // Super Admin - ทุกสิทธิ์
            $superAdminRole = Role::where('name', 'super-admin')->first();
            if ($superAdminRole) {
                $superAdminRole->givePermissionTo(array_keys($permissions));
            }
            
            // Admin - สิทธิ์เกือบทั้งหมด
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminPermissions = [
                    'job-list', 'job-create', 'job-edit', 'job-delete', 'job-status-toggle',
                    'job-lead-list', 'job-lead-create', 'job-lead-edit', 'job-lead-delete',
                    'job-lead-status-update', 'job-lead-force-unlock', 'job-lead-bulk-update',
                    'job-lead-admin', 'job-statistics', 'job-dashboard'
                ];
                $adminRole->givePermissionTo($adminPermissions);
            }
            
            // HR Manager - จัดการใบสมัครและดูสถิติ
            $hrRole = Role::where('name', 'hr-manager')->first();
            if (!$hrRole) {
                $hrRole = Role::create(['name' => 'hr-manager', 'guard_name' => 'web']);
            }
            $hrPermissions = [
                'job-list', 'job-create', 'job-edit', 'job-status-toggle',
                'job-lead-list', 'job-lead-create', 'job-lead-edit',
                'job-lead-status-update', 'job-lead-bulk-update',
                'job-statistics', 'job-dashboard'
            ];
            $hrRole->givePermissionTo($hrPermissions);
            
            // HR Staff - ใช้งานพื้นฐาน
            $staffRole = Role::where('name', 'hr-staff')->first();
            if (!$staffRole) {
                $staffRole = Role::create(['name' => 'hr-staff', 'guard_name' => 'web']);
            }
            $staffPermissions = [
                'job-list', 'job-lead-list', 'job-lead-create',
                'job-lead-edit', 'job-lead-status-update', 'job-dashboard'
            ];
            $staffRole->givePermissionTo($staffPermissions);
            
        } catch (\Exception $e) {
            // ถ้ามี Role ไม่พบ จะข้ามไป
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ Permissions ที่สร้างขึ้น
        $permissions = [
            'job-list', 'job-create', 'job-edit', 'job-delete', 'job-status-toggle',
            'job-lead-list', 'job-lead-create', 'job-lead-edit', 'job-lead-delete',
            'job-lead-status-update', 'job-lead-force-unlock', 'job-lead-bulk-update',
            'job-lead-admin', 'job-statistics', 'job-dashboard'
        ];
        
        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }
        
        // ลบ Role ที่สร้างขึ้น (ถ้ามี)
        Role::where('name', 'hr-manager')->delete();
        Role::where('name', 'hr-staff')->delete();
    }
};
