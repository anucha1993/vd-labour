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
        Schema::create('job_leads', function (Blueprint $table) {
            $table->id('job_lead_id');
            $table->string('job_lead_number')->unique()->comment('Running number format: country_code+YYYY+XXXX');
            $table->unsignedBigInteger('job_id');
            $table->bigInteger('lead_id')->comment('ID ของคนงาน');
            $table->enum('job_lead_status', [
                'ร่าง', 'ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์',
                'เสนองาน', 'ตอบรับ', 'ปฏิเสธ', 'ถอน'
            ])->default('ร่าง');
            $table->text('remarks')->nullable()->comment('หมายเหตุ/เหตุผล');
            $table->boolean('is_locked')->default(false)->comment('สถานะล็อคคนงาน');
            $table->datetime('locked_at')->nullable()->comment('เวลาที่ล็อค');
            $table->datetime('unlocked_at')->nullable()->comment('เวลาที่ปลดล็อค');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            
            // Foreign key constraints (แก้ไขให้ตรงกับโครงสร้างจริง)
            // ลบ foreign key constraints ออกชั่วคราว จนกว่าจะตรวจสอบโครงสร้างตารางได้
            // $table->foreign('job_id')->references('job_id')->on('jobs')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
            
            // Indexes
            $table->index('job_lead_number');
            $table->index('job_id');
            $table->index('lead_id');
            $table->index('job_lead_status');
            $table->index('is_locked');
            
            // Unique constraint to prevent duplicate lead in same job
            $table->unique(['job_id', 'lead_id'], 'unique_job_lead');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_leads');
    }
};
