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
        Schema::create('procurement_committees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bid_id')->nullable()->comment('When selecting in bid analysis');
            $table->unsignedBigInteger('bid_committee_id')->nullable()->comment('When selecting in bid analysis');
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->text('signature')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('procurement_committees');
    }
};
