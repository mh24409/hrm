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
        Schema::create('employee_salary_types', function (Blueprint $table) {            
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('setup_rule_id');
            $table->string('type');
            $table->double('amount')->nullable();
            $table->boolean('on_gross')->default(0);
            $table->boolean('on_basic')->default(0);
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
        Schema::dropIfExists('employee_salary_types');
    }
};
