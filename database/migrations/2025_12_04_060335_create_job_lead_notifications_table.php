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
        Schema::create('job_lead_notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->unsignedBigInteger('job_lead_id');
            $table->string('notification_type'); // '7_days', '14_days', '21_days', 'over_21_days'
            $table->string('current_status'); // สถานะขณะที่แจ้งเตือน
            $table->timestamp('sent_at'); // วันที่แจ้งเตือน
            $table->timestamp('responded_at')->nullable(); // วันที่ผู้ใช้ตอบสนอง
            $table->string('response')->nullable(); // 'wait' หรือ 'withdraw'
            $table->unsignedBigInteger('responded_by')->nullable(); // user ที่ตอบสนอง
            $table->boolean('is_read')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('job_lead_id')->references('job_lead_id')->on('job_leads')->onDelete('cascade');
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['job_lead_id', 'notification_type']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_lead_notifications');
    }
};
