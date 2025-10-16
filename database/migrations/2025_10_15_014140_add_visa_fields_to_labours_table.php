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
        Schema::table('labours', function (Blueprint $table) {
            $table->date('labour_visa_submit_date')->nullable()->comment('วันที่ยืนวีซ่า');
            $table->date('labour_visa_approved_date')->nullable()->comment('วันที่ Approved VISA');
            $table->enum('labour_visa_status', ['none', 'approved', 'rejected'])->default('none')->comment('Status Visa');
            $table->text('labour_visa_note')->nullable()->comment('Visa Note กรณี Status rejected');
            $table->date('labour_visa_reject_date')->nullable()->comment('วันที่ Reject VISA');
            $table->date('labour_visa_start_date')->nullable()->comment('วันที่ออก VISA หรือ วันที่เริ่มต้น VISA');
            $table->string('labour_visa_file')->nullable()->comment('ไฟล์เอกสาร VISA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labours', function (Blueprint $table) {
            $table->dropColumn([
                'labour_visa_submit_date',
                'labour_visa_approved_date',
                'labour_visa_status',
                'labour_visa_note',
                'labour_visa_reject_date',
                'labour_visa_start_date',
                'labour_visa_file'
            ]);
        });
    }
};
