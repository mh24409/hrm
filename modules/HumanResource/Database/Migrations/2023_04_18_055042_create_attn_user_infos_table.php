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
        Schema::create('attn_user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->unsignedBigInteger('role');
            $table->string('password');
            $table->string('employee_device_id');
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
        Schema::dropIfExists('attn_user_infos');
    }
};
