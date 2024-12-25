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
        Schema::create('employee_performance_criterias', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->unsignedBigInteger('performance_type_id');
            $table->unsignedBigInteger('evaluation_type_id');
            $table->string('title');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('employee_performance_criterias');
    }
};
