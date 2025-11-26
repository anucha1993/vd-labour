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
        Schema::create('leads', function (Blueprint $table) {
            $table->id('lead_id');
            
            // Personal Information
            $table->string('lead_prefix', 50)->nullable();
            $table->string('lead_firstname', 255);
            $table->string('lead_lastname', 255);
            $table->enum('lead_gender', ['male', 'female'])->default('male');
            $table->enum('lead_marital_status', ['single', 'married', 'divorced'])->nullable();
            $table->date('lead_birthday')->nullable();
            $table->integer('lead_age')->nullable();
            $table->decimal('lead_height', 5, 2)->nullable();
            $table->decimal('lead_weight', 5, 2)->nullable();
            $table->decimal('lead_bmi', 5, 2)->nullable();
            $table->string('lead_phone', 50)->nullable();
            $table->string('lead_phone_2', 50)->nullable();
            $table->string('lead_email', 255)->nullable();
            $table->text('lead_address')->nullable();
            
            // Passport & ID
            $table->string('lead_passport_number', 100)->nullable();
            $table->date('lead_passport_issue_date')->nullable();
            $table->date('lead_passport_expiry_date')->nullable();
            $table->string('lead_id_card_number', 50)->nullable();
            
            // Clothing Sizes
            $table->string('lead_shirt_size', 20)->nullable();
            $table->string('lead_pant_size', 20)->nullable();
            $table->string('lead_shoes_size', 20)->nullable();
            
            // Education
            $table->enum('lead_education', [
                'elementary',
                'junior_high',
                'high_school',
                'voc_cert',
                'high_voc_cert',
                'bachelor'
            ])->nullable();
            
            // Language Skills
            $table->enum('lead_chinese_speaking', ['no', 'beginner', 'intermediate', 'advance'])->default('no');
            $table->enum('lead_english_speaking', ['no', 'beginner', 'intermediate', 'advance'])->default('no');
            $table->string('lead_other_language', 255)->nullable();
            
            // Additional Information
            $table->enum('lead_work_israel', ['no', 'yes'])->default('no');
            $table->text('lead_work_israel_details')->nullable();
            $table->enum('lead_criminal_history', ['no', 'yes'])->default('no');
            $table->text('lead_criminal_details')->nullable();
            $table->enum('lead_eyesight', ['normal', 'abnormal'])->default('normal');
            $table->enum('lead_color_blindness', ['no', 'yes'])->default('no');
            
            // Emergency Contact
            $table->string('lead_emergency_name', 255)->nullable();
            $table->string('lead_emergency_phone', 50)->nullable();
            $table->string('lead_emergency_status', 100)->nullable();
            
            // Driving License
            $table->enum('lead_driving_license', ['no', 'yes'])->default('no');
            $table->string('lead_car_type', 100)->nullable();
            $table->date('lead_license_valid_until')->nullable();
            
            // Position & Skills (ใช้ร่วมกับ labour)
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('position_id_2')->nullable();
            $table->unsignedBigInteger('position_id_3')->nullable();
            $table->json('lead_skills')->nullable();
            
            // Country & Job Group (ใช้ร่วมกับ labour)
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('job_group_id')->nullable();
            
            // Lead Status
            $table->enum('lead_status', ['new', 'contacted', 'interview', 'qualified', 'converted', 'rejected'])->default('new');
            $table->text('lead_note')->nullable();
            
            // Staff assignment
            $table->unsignedBigInteger('staff_id')->nullable();
            
            // Photo
            $table->string('lead_photo', 500)->nullable();
            
            // Conversion tracking
            $table->unsignedBigInteger('labour_id')->nullable();
            $table->timestamp('converted_at')->nullable();
            
            // Date & Location (from form)
            $table->string('lead_date_location', 255)->nullable();
            $table->string('lead_recommender', 255)->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('position_id')->references('position_id')->on('position')->onDelete('set null');
            $table->foreign('position_id_2')->references('position_id')->on('position')->onDelete('set null');
            $table->foreign('position_id_3')->references('position_id')->on('position')->onDelete('set null');
            $table->foreign('country_id')->references('country_id')->on('country')->onDelete('set null');
            $table->foreign('job_group_id')->references('job_group_id')->on('job_group')->onDelete('set null');
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('set null');
            $table->foreign('labour_id')->references('labour_id')->on('labours')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
