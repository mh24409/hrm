<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_points', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('employee_id')->nullable()->comment('employee id');
            $table->string('attendance')->nullable()->comment('attendance points');
            $table->string('management')->nullable()->comment('management points');
            $table->string('collaborative')->nullable()->comment('collaborative points');
            $table->unsignedBigInteger('total')->nullable();
            $table->date('date')->nullable()->comment('pointing date');
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
        Schema::dropIfExists('reward_points');
    }
};
