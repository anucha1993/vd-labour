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
        Schema::create('job_lead_activities', function (Blueprint $table) {
            $table->id('activity_id');
            $table->unsignedBigInteger('job_lead_id');
            $table->string('activity_type'); // created, status_changed, updated, deleted, bulk_updated
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('reason')->nullable(); // เหตุผล (จำเป็นสำหรับ ปฏิเสธ และ ถอน)
            $table->text('remarks')->nullable(); // หมายเหตุเพิ่มเติม
            $table->json('changes')->nullable(); // เก็บข้อมูลการเปลี่ยนแปลงอื่นๆ
            $table->unsignedBigInteger('user_id'); // ผู้ทำรายการ
            $table->timestamps();

            // Foreign keys
            $table->foreign('job_lead_id')->references('job_lead_id')->on('job_leads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index
            $table->index('job_lead_id');
            $table->index('activity_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_lead_activities');
    }
};
