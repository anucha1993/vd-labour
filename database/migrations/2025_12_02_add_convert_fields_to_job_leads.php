<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_leads', function (Blueprint $table) {
            // เพิ่มคอลัมน์สถานะการ Convert
            if (!Schema::hasColumn('job_leads', 'convert_status')) {
                $table->enum('convert_status', ['pending', 'completed', 'failed'])->default('pending')->after('remarks')->comment('สถานะการ Convert: pending=รอ, completed=สำเร็จ, failed=ล้มเหลว');
                $table->index('convert_status');
            }

            if (!Schema::hasColumn('job_leads', 'converted_at')) {
                $table->datetime('converted_at')->nullable()->after('convert_status')->comment('วันเวลาที่ Convert สำเร็จ');
            }

            if (!Schema::hasColumn('job_leads', 'labour_id')) {
                $table->unsignedBigInteger('labour_id')->nullable()->after('converted_at')->comment('ID ของ Labour ที่ Convert ไป');
                $table->index('labour_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_leads', function (Blueprint $table) {
            $table->dropIndex(['convert_status']);
            $table->dropIndex(['labour_id']);
            $table->dropColumn(['convert_status', 'converted_at', 'labour_id']);
        });
    }
};
