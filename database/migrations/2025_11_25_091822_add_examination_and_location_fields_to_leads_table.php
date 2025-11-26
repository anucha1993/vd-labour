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
            $table->unsignedBigInteger('examination_round_id')->nullable();
            $table->unsignedBigInteger('location_test_id')->nullable();
            
            $table->foreign('examination_round_id')->references('examination_round_id')->on('examination_round')->onDelete('set null');
            $table->foreign('location_test_id')->references('location_test_id')->on('location_test')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['examination_round_id']);
            $table->dropForeign(['location_test_id']);
            $table->dropColumn(['examination_round_id', 'location_test_id']);
        });
    }
};
