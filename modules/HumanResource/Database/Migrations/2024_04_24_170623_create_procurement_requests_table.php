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
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->id();
            $table->string('serial', 50)->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('employee_id');
            $table->text('request_reason')->nullable();
            $table->date('expected_start_date')->nullable();
            $table->date('expected_end_date')->nullable();
            $table->tinyInteger('is_approve')->default(0)->comment('Check request is approved or not');
            $table->text('approval_reason')->nullable()->comment('Reason for approval');
            $table->tinyInteger('is_quoted')->default(0)->comment('0= not quoted , 1 = quoted');
            $table->string('pdf_link')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procurement_requests');
    }
};
