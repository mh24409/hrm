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
        Schema::create('point_management', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('employee_id')->nullable();
            $table->string('point_category')->nullable();
            $table->text('description')->nullable();
            $table->string('point')->nullable();
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
        Schema::dropIfExists('point_management');
    }
};
