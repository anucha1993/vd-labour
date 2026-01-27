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
        Schema::table('labours', function (Blueprint $table) {
            $table->string('labour_id_card_number')->nullable()->after('labour_phone')->comment('เลขที่บัตร ปปช');
            $table->date('labour_id_card_expiry')->nullable()->after('labour_id_card_number')->comment('วันหมดอายุบัตร ปปช');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labours', function (Blueprint $table) {
            $table->dropColumn(['labour_id_card_number', 'labour_id_card_expiry']);
        });
    }
};
