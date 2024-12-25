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
        Schema::create('procurement_bid_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->date('create_date')->nullable();
            $table->string('sba_no')->nullable();
            $table->text('location')->nullable();
            $table->text('attachment')->nullable();
            $table->double('total')->default(0);
            $table->string('pdf_link')->nullable();
            $table->updateCreatedBy();
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
        Schema::dropIfExists('procurement_bid_analyses');
    }
};
