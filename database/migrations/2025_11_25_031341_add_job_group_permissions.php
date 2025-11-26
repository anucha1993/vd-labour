<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // สร้าง Permissions สำหรับจัดการกลุ่มงาน (Job Group)
        $permissions = [
            'view job-group',
            'create job-group',
            'update job-group',
            'delete job-group',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ Permissions ที่สร้างไว้
        $permissions = [
            'view job-group',
            'create job-group',
            'update job-group',
            'delete job-group',
        ];

        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }
    }
};
