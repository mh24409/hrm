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
        Schema::create('procurement_goods_receiveds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->unsignedBigInteger('acc_coa_id')->nullable();
            $table->date('created_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->double('total_quantity')->nullable();
            $table->double('total')->nullable();
            $table->double('discount')->nullable();
            $table->double('grand_total')->nullable();
            $table->text('received_by_signature')->nullable();
            $table->string('received_by_name')->nullable();
            $table->string('received_by_title')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable()->comment('id from acc_voucher table');
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
        Schema::dropIfExists('procurement_goods_receiveds');
    }
};
