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
        Schema::table('leads', function (Blueprint $table) {
            // Add foreign key column for staff_sub only if it doesn't exist
            if (!Schema::hasColumn('leads', 'lead_recommender_staff_sub_id')) {
                $table->unsignedBigInteger('lead_recommender_staff_sub_id')->nullable()->after('staff_id');
                $table->foreign('lead_recommender_staff_sub_id')->references('staff_sub_id')->on('staff_sub')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'lead_recommender_staff_sub_id')) {
                $table->dropForeign(['lead_recommender_staff_sub_id']);
                $table->dropColumn('lead_recommender_staff_sub_id');
            }
        });
    }
};
