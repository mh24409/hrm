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
        Schema::create('employee_allowence_deductions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->unsignedBigInteger('setup_rule_id');
            $table->unsignedBigInteger('employee_id');
            $table->double('amount')->nullable();
            $table->double('percentage')->nullable();
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
        Schema::dropIfExists('employee_allowence_deductions');
    }
};
