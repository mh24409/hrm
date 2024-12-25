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
        Schema::create('employee_performance_values', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->unsignedBigInteger('performance_id');
            $table->unsignedBigInteger('performance_type_id')->nullable();
            $table->unsignedBigInteger('performance_criteria_id')->nullable();
            $table->string('emp_perform_eval')->nullable();
            $table->integer('score')->nullable();
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('employee_performance_values');
    }
};
