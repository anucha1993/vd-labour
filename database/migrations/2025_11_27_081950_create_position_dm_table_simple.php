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
        Schema::create('position_dm', function (Blueprint $table) {
            $table->bigIncrements('position_dm_id');
            $table->unsignedBigInteger('dm_id');
            $table->unsignedBigInteger('position_id');
            $table->decimal('position_dm_amount', 6, 2);
            $table->string('position_dm_period');
            $table->string('position_dm_age');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_dm');
    }
};
