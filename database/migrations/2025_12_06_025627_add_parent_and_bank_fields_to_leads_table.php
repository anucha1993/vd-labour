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
            $table->string('lead_father_name')->nullable()->after('lead_lastname');
            $table->string('lead_mother_name')->nullable()->after('lead_father_name');
            $table->string('lead_bank_account_number')->nullable()->after('lead_emergency_status');
            $table->string('lead_bank_name')->nullable()->after('lead_bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['lead_father_name', 'lead_mother_name', 'lead_bank_account_number', 'lead_bank_name']);
        });
    }
};
