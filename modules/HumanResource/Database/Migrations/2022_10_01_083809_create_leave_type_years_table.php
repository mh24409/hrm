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
        Schema::create('leave_type_years', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('employee_id');
            $table->foreignId('leave_type_id');
            $table->foreignId('academic_year_id');
            $table->integer('entitled')->nullable();
            $table->integer('taken')->default(0);
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
        Schema::dropIfExists('leave_type_years');
    }
};
