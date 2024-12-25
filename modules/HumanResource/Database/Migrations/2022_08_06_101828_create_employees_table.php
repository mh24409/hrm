<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('user_id');
            $table->string('employee_id');
            $table->string('card_no')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->longText('profile_image')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->integer('employee_group_id')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('degree_name')->nullable();
            $table->string('university_name')->nullable();
            $table->string('cgp')->nullable()->nullable();
            $table->string('passing_year')->nullable();
            $table->string('company_name')->nullable();
            $table->string('working_period')->nullable();
            $table->string('duties')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('signature')->nullable();
            $table->boolean('is_admin')->default(0)->nullable();
            $table->string('maiden_name')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city')->nullable();
            $table->integer('zip')->nullable();
            $table->integer('citizenship')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('promotion_date')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('termination_reason')->nullable();
            $table->string('national_id')->nullable();
            $table->string('identification_attachment')->nullable();
            $table->string('nationality')->nullable();
            $table->integer('voluntary_termination')->nullable();
            $table->date('rehire_date')->nullable();
            $table->double('rate')->nullable();
            $table->unsignedBigInteger('pay_frequency_id')->nullable();
            $table->unsignedBigInteger('duty_type_id')->nullable();
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->unsignedBigInteger('marital_status_id')->nullable();
            $table->unsignedInteger('attendance_time_id')->nullable();
            $table->unsignedBigInteger('employee_type_id')->nullable();

            $table->date('contract_start_date')->nullable()->comment('if duty type is contractual');
            $table->date('contract_end_date')->nullable()->comment('if duty type is contractual');

            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sub_department_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('employee_code')->nullable();
            $table->string('employee_device_id')->nullable();

            $table->string('highest_educational_qualification')->nullable();
            $table->string('pay_frequency_text')->nullable();
            $table->float('hourly_rate')->nullable();
            $table->float('hourly_rate2')->nullable();
            $table->string('home_department')->nullable();
            $table->string('department_text')->nullable();
            $table->string('class_code')->nullable();
            $table->string('class_code_desc')->nullable();
            $table->date('class_acc_date')->nullable();
            $table->boolean('class_status')->default(1);
            $table->boolean('is_supervisor')->default(0);
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->text('supervisor_report')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('ethnic_group')->nullable();
            $table->string('eeo_class_gp')->nullable();
            $table->string('ssn')->nullable();
            $table->string('work_in_city')->nullable();
            $table->integer('live_in_state')->nullable();
            $table->string('home_email')->nullable();
            $table->string('business_email')->nullable();
            $table->string('home_phone')->nullable()->nullable();
            $table->string('business_phone')->nullable();
            $table->string('cell_phone')->nullable();

            $table->string('emergency_contact_person')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_country')->nullable();
            $table->string('emergency_contact_state')->nullable();
            $table->string('emergency_contact_city')->nullable();
            $table->string('emergency_contact_post_code')->nullable();
            $table->longText('emergency_contact_address')->nullable();

            $table->string('present_address_country')->nullable();
            $table->string('present_address_state')->nullable();
            $table->string('present_address_city')->nullable();
            $table->string('present_address_post_code')->nullable();
            $table->longText('present_address_address')->nullable();

            $table->string('permanent_address_country')->nullable();
            $table->string('permanent_address_state')->nullable();
            $table->string('permanent_address_city')->nullable();
            $table->string('permanent_address_post_code')->nullable();
            $table->longText('permanent_address_address')->nullable();

            $table->string('skill_type')->nullable();
            $table->string('skill_name')->nullable();
            $table->string('certificate_type')->nullable();
            $table->string('certificate_name')->nullable();
            $table->string('skill_attachment')->nullable();

            $table->string('emergency_contact')->nullable();
            $table->string('emergency_home_phone')->nullable();
            $table->string('emergency_work_phone')->nullable();
            $table->string('alter_emergency_contact')->nullable();
            $table->string('alter_emergency_home_phone')->nullable();
            $table->string('alter_emergency_work_phone')->nullable();
            $table->string('sos')->nullable();
            $table->string('monthly_work_hours')->nullable();
            $table->string('employee_grade')->nullable();
            $table->string('religion')->nullable();
            $table->integer('no_of_kids')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('health_condition')->nullable();
            $table->boolean('is_disable')->default(0)->nullable();
            $table->string('disabilities_desc')->nullable();
            $table->string('profile_img_name')->nullable();
            $table->string('profile_img_location')->nullable();
            $table->string('national_id_no')->nullable();
            $table->string('iqama_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('driving_license_no')->nullable();
            $table->boolean('work_permit')->default(0);
            $table->boolean('is_active')->default(1)->nullable();
            $table->boolean('is_left')->default(0)->nullable();
            $table->updateCreatedBy();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
