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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('loan_no')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('permission_by_id');
            $table->double('amount');
            $table->double('interest_rate');
            $table->double('installment');
            $table->integer('installment_period')->default(0)->comment('Number of Installment');
            $table->integer('installment_cleared')->default(0)->comment('Number of Installment Cleard from salary generate');
            $table->double('repayment_amount');
            $table->double('released_amount')->nullable();
            $table->integer('release')->nullable();
            $table->date('approved_date');
            $table->date('repayment_start_date');
            $table->text('loan_details')->nullable();
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
        Schema::dropIfExists('loans');
    }
};
