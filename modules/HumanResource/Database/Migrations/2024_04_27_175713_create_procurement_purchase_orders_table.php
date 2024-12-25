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
        Schema::create('procurement_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_received_id')->nullable()->comment('After received the goods id will fill here');
            $table->date('created_date')->nullable();
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->string('vendor_name')->nullable()->comment('vendor or company');
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            $table->double('total')->nullable();
            $table->double('discount')->nullable();
            $table->double('grand_total')->nullable();
            $table->text('notes')->nullable();
            $table->string('authorizer_name')->nullable();
            $table->string('authorizer_title')->nullable();
            $table->text('authorizer_signature')->nullable();
            $table->date('authorizer_date')->nullable();
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
        Schema::dropIfExists('procurement_purchase_orders');
    }
};
