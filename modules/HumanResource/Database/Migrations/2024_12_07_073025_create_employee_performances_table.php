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
        Schema::create('employee_performances', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->boolean('is_teacher')->default(0);
            $table->string('class_name')->nullable();
            $table->unsignedBigInteger('employee_id')->index();
            $table->string('performance_code')->nullable();
            $table->string('position_of_supervisor')->nullable();
            $table->string('review_period')->nullable();
            $table->string('note')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('note_by')->nullable();
            $table->integer('score')->nullable();
            $table->double('total_score')->nullable();
            $table->integer('number_of_star')->nullable();
            $table->string('employee_comments')->nullable();
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
        Schema::dropIfExists('employee_performances');
    }
};
