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
        Schema::create('employee_files', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('employee_id');
            $table->string('tin_no')->nullable();
            $table->double('gross_salary');
            $table->double('basic');
            $table->double('transport')->nullable();
            $table->double('house_rent')->nullable();
            $table->double('medical')->nullable();
            $table->double('other_allowance')->nullable();
            $table->double('state_income_tax')->nullable();
            $table->double('soc_sec_npf_tax')->nullable();
            $table->double('loan_deduct')->nullable();
            $table->double('salary_advance')->nullable();
            $table->double('lwp')->nullable();
            $table->double('pf')->nullable();
            $table->double('stamp')->nullable();
            $table->double('medical_benefit')->nullable();
            $table->double('family_benefit')->nullable();
            $table->double('transportation_benefit')->nullable();
            $table->double('other_benefit')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('employee_files');
    }
};
