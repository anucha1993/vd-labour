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
            // Drop existing foreign key
            $table->dropForeign(['job_lead_id']);
            
            // Make job_lead_id nullable
            $table->unsignedBigInteger('job_lead_id')->nullable()->change();
            
            // Add new foreign key with SET NULL on delete
            $table->foreign('job_lead_id')
                  ->references('job_lead_id')
                  ->on('job_leads')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_lead_activities', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['job_lead_id']);
            
            // Make job_lead_id not nullable
            $table->unsignedBigInteger('job_lead_id')->nullable(false)->change();
            
            // Restore old foreign key with CASCADE
            $table->foreign('job_lead_id')
                  ->references('job_lead_id')
                  ->on('job_leads')
                  ->onDelete('cascade');
        });
    }
};
