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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('sender_id')->comment('User or employee who will send the message');
            $table->unsignedBigInteger('receiver_id')->comment('User or employee who will send the message');
            $table->string('subject');
            $table->text('message');
            $table->dateTime('datetime');
            $table->tinyInteger('sender_status')->default(0)->comment('0=unseen, 1=seen, 2=delete');
            $table->tinyInteger('receiver_status')->default(0)->comment('0=unseen, 1=seen, 2=delete');
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
        Schema::dropIfExists('messages');
    }
};
