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
        // สร้าง Permissions สำหรับจัดการตำแหน่งงาน (Position)
        $permissions = [
            'view position',
            'create position',
            'update position',
            'delete position',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ Permissions ที่สร้างไว้
        $permissions = [
            'view position',
            'create position',
            'update position',
            'delete position',
        ];

        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }
    }
};
