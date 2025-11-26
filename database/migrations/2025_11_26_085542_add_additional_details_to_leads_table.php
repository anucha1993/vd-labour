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
            $table->text('lead_additional_details')->nullable()->after('lead_color_blindness')->comment('รายละเอียดเพิ่มเติมเกี่ยวกับสุขภาพ ประสบการณ์ ทักษะ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('lead_additional_details');
        });
    }
};
