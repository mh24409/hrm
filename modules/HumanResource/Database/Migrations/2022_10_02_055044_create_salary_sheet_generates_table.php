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
        Schema::create('salary_sheet_generates', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('name');
            $table->dateTime('generate_date');
            $table->unsignedBigInteger('generate_by_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_approved')->default(0);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();
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
        Schema::dropIfExists('salary_sheet_generates');
    }
};
