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
        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'job_group_id')) {
                $table->unsignedBigInteger('job_group_id')->nullable()->after('dm_id');
                $table->index('job_group_id');
            }

            if (!Schema::hasColumn('jobs', 'position_id')) {
                $table->unsignedBigInteger('position_id')->nullable()->after('job_group_id');
                $table->index('position_id');
            }

            // Foreign keys are intentionally omitted to avoid migration issues in existing DBs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['job_group_id']);
            $table->dropIndex(['position_id']);
            $table->dropColumn(['job_group_id', 'position_id']);
        });
    }
};
