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
        Schema::create('apply_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('employee_id');
            $table->foreignId('leave_type_id');
            $table->foreignId('academic_year_id')->nullable();
            $table->date('leave_apply_start_date')->nullable();
            $table->date('leave_apply_end_date')->nullable();
            $table->date('leave_apply_date');
            $table->integer('total_apply_day')->nullable();
            $table->date('leave_approved_start_date')->nullable();
            $table->date('leave_approved_end_date')->nullable();
            $table->integer('total_approved_day')->nullable();
            $table->boolean('is_approved_by_manager')->default(0);
            $table->unsignedBigInteger('approved_by_manager')->nullable();
            $table->date('manager_approved_date')->nullable();
            $table->string('manager_approved_description')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->foreignId('approved_by')->nullable();
            $table->date('leave_approved_date')->nullable();
            $table->string('reason')->nullable();
            $table->text('location')->nullable();
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
        Schema::dropIfExists('apply_leaves');
    }
};
