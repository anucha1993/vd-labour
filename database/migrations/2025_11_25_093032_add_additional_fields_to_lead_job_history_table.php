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
        Schema::table('lead_job_history', function (Blueprint $table) {
            $table->string('start_date', 20)->nullable()->after('experience_years');
            $table->string('end_date', 20)->nullable()->after('start_date');
            $table->text('description')->nullable()->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_job_history', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'description']);
        });
    }
};
