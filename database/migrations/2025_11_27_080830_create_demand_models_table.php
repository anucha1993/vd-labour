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
        Schema::create('demands', function (Blueprint $table) {
            $table->bigIncrements('dm_id');
            $table->date('dm_issue_date');
            $table->string('dm_let_no');
            $table->string('dm_com_name');
            $table->text('dm_com_addr');
            $table->string('dm_reg_no');
            $table->unsignedBigInteger('dm_indust_type');
            $table->string('dm_bmi')->nullable();
            $table->string('dm_time_work')->nullable();
            $table->text('dm_sa')->nullable();
            $table->text('dm_job')->nullable();
            $table->enum('dm_exp', ['accomm', 'food', 'med', 'shuttle'])->nullable();
            $table->timestamps();
            
            $table->foreign('dm_indust_type')->references('industry_type_id')->on('inducstry_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demands');
    }
};
