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
        if (!Schema::hasColumn('customers', 'customer_address')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->text('customer_address')->nullable();
            });
        }
        
        if (!Schema::hasColumn('customers', 'customer_registration_no')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('customer_registration_no', 100)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_address', 'customer_registration_no']);
        });
    }
};
