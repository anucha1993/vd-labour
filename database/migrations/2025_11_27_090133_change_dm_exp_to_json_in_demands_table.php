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
        Schema::table('demands', function (Blueprint $table) {
            // Change dm_exp from enum to json
            $table->json('dm_exp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demands', function (Blueprint $table) {
            // Revert back to enum
            $table->enum('dm_exp', ['accomm', 'food', 'med', 'shuttle'])->nullable()->change();
        });
    }
};
