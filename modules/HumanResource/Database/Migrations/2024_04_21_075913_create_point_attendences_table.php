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
        Schema::create('point_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('employee_id');
            $table->string('in_time');
            $table->string('point');
            $table->text('description');
            $table->date('create_date')->nullable()->comment('attendance date');
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
        Schema::dropIfExists('point_attendances');
    }
};
