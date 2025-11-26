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
            $table->dropForeign(['location_test_id']);
            $table->dropColumn('location_test_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('location_test_id')->nullable();
            $table->foreign('location_test_id')->references('location_test_id')->on('location_test')->onDelete('set null');
        });
    }
};
