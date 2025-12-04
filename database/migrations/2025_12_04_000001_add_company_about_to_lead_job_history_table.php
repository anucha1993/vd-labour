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
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('lead_job_history', 'company_about')) {
                $table->text('company_about')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_job_history', function (Blueprint $table) {
            $table->dropColumn('company_about');
        });
    }
};
