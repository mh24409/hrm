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
        Schema::create('attn_checkin_checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('state');
            $table->timestamp('timestamp');
            $table->integer('type');
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
        Schema::dropIfExists('attn_checkin_checkouts');
    }
};
