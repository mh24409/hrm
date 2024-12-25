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
        Schema::create('employee_performance_development_plans', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('performance_id');
            $table->longText('recommend_areas')->nullable();
            $table->longText('expected_outcomes')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('employee_performance_development_plans');
    }
};
