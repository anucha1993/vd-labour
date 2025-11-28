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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id('job_id');
            $table->string('job_number')->unique()->comment('Running number format: JOB+YYYY+XXXX');
            $table->string('job_name');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('dm_id');
            $table->integer('job_total')->comment('จำนวนเปิดรับสมัคร');
            $table->date('job_start_date')->comment('เริ่มเปิดรับสมัคร');
            $table->date('job_end_date')->nullable()->comment('วันปิดรับสมัคร หากรับสมัครต่อเนื่องไม่ต้องระบุ');
            $table->enum('job_status', ['เปิดรับสมัคร', 'ปิดรับสมัคร'])->default('เปิดรับสมัคร');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            
            // Foreign key constraints (แก้ไขให้ตรงกับโครงสร้างจริง)
            // ลบ foreign key constraints ออกชั่วคราว จนกว่าจะตรวจสอบโครงสร้างตารางได้
            // $table->foreign('country_id')->references('country_id')->on('country');
            // $table->foreign('dm_id')->references('dm_id')->on('demands');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
            
            // Indexes
            $table->index('job_number');
            $table->index('country_id');
            $table->index('dm_id');
            $table->index('job_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
