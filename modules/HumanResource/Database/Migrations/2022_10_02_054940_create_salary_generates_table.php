<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_generates', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('employee_id');
            $table->foreignId('loan_id')->nullable();
            $table->foreignId('salary_advanced_id')->nullable();
            $table->string('salary_month_year');
            $table->string('tin_no')->nullable();
            $table->string('total_attendance');
            $table->double('total_count', 15, 2);
            $table->double('attendance_allowance', 15, 2)->nullable();
            $table->double('gross', 15, 2);
            $table->double('basic', 15, 2);
            $table->double('transport', 15, 2)->nullable();
            $table->double('total_allowance', 15, 2)->nullable();
            $table->double('total_deduction', 15, 2)->nullable();
            $table->double('gross_salary', 15, 2);
            $table->double('income_tax', 15, 2)->nullable();
            $table->double('soc_sec_npf_tax', 15, 2)->nullable();
            $table->double('employer_contribution', 15, 2)->nullable();
            $table->double('icf_amount', 15, 2)->nullable();
            $table->double('loan_deduct', 15, 2)->nullable();
            $table->double('salary_advance', 15, 2)->nullable();
            $table->double('leave_without_pay', 15, 2)->nullable();
            $table->double('provident_fund', 15, 2)->nullable();
            $table->double('stamp', 15, 2)->nullable();
            $table->double('net_salary', 15, 2);
            $table->double('medical_benefit', 15, 2)->nullable();
            $table->double('family_benefit', 15, 2)->nullable();
            $table->double('transportation_benefit', 15, 2)->nullable();
            $table->double('other_benefit', 15, 2)->nullable();
            $table->double('normal_working_hrs_month')->nullable();
            $table->double('actual_working_hrs_month')->nullable();
            $table->double('hourly_rate_basic', 15, 2)->nullable();
            $table->double('hourly_rate_trasport_allowance', 15, 2)->nullable();
            $table->double('basic_salary_pro_rated', 15, 2)->nullable();
            $table->double('transport_allowance_pro_rated', 15, 2)->nullable();
            $table->double('basic_transport_allowance', 15, 2)->nullable();
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
        Schema::dropIfExists('salary_generates');
    }
};
