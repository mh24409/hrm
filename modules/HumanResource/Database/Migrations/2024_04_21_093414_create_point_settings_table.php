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
        Schema::create('point_settings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('general_point')->nullable()->comment('Maximum limit for collaborative points');
            $table->string('attendance_point')->nullable();
            $table->string('attendance_start')->nullable();
            $table->string('attendance_end')->nullable();
            $table->date('collaborative_start')->nullable();
            $table->date('collaborative_end')->nullable();
            $table->updateCreatedBy();
            $table->softDeletes();
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
        Schema::dropIfExists('point_settings');
    }
};
