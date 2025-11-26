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
        Schema::create('lead_job_history', function (Blueprint $table) {
            $table->id('job_history_id');
            $table->unsignedBigInteger('lead_id');
            $table->string('company_type', 255);
            $table->string('company_name', 255)->nullable();
            $table->string('position', 255);
            $table->string('country', 100)->default('THAI');
            $table->integer('experience_years')->default(0);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->foreign('lead_id')->references('lead_id')->on('leads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_job_history');
    }
};
