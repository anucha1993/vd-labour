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
            $table->string('lead_firstname_th', 255)->nullable()->after('lead_lastname');
            $table->string('lead_lastname_th', 255)->nullable()->after('lead_firstname_th');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['lead_firstname_th', 'lead_lastname_th']);
        });
    }
};
