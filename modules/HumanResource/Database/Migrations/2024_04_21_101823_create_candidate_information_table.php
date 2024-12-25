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
        Schema::create('candidate_information', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_rand_id', 20);
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50);
            $table->string('alternative_phone', 50)->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->text('picture')->nullable();
            $table->string('ssn', 50)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->integer('zip')->nullable();
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
        Schema::dropIfExists('candidate_information');
    }
};
