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
        Schema::table('job_lead_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id')->nullable()->after('job_lead_id')->comment('เก็บ lead_id ไว้เพื่อดูประวัติแม้ job_lead ถูกยกเลิก');
            $table->string('job_lead_number')->nullable()->after('lead_id')->comment('เก็บเลขที่ใบสมัครไว้แม้ถูกยกเลิก');
            
            $table->index('lead_id');
        });
        
        // Update existing records - copy lead_id and job_lead_number from job_leads
        DB::statement('
            UPDATE job_lead_activities jla
            INNER JOIN job_leads jl ON jla.job_lead_id = jl.job_lead_id
            SET jla.lead_id = jl.lead_id,
                jla.job_lead_number = jl.job_lead_number
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_lead_activities', function (Blueprint $table) {
            $table->dropIndex(['lead_id']);
            $table->dropColumn(['lead_id', 'job_lead_number']);
        });
    }
};
