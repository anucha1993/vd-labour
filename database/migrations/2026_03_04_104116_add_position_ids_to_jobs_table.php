<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->json('position_ids')->nullable()->after('position_id');
        });

        // Migrate existing position_id data to position_ids
        DB::table('jobs')->whereNotNull('position_id')->orderBy('job_id')->each(function ($job) {
            DB::table('jobs')->where('job_id', $job->job_id)->update([
                'position_ids' => json_encode([$job->position_id])
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('position_ids');
        });
    }
};
