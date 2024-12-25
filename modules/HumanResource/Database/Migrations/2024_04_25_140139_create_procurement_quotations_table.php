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
        Schema::create('procurement_quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('bid_analysis_id')->nullable()->comment('After using this quote in Bid, the bid id will fill here');
            $table->unsignedBigInteger('purchase_order_id')->nullable()->comment('After using this quote in purchase order, the purchase id will fill here');
            $table->string('company_name')->nullable()->comment('vendor named as company');
            $table->unsignedBigInteger('vendor_id');
            $table->date('date')->nullable();
            $table->string('address')->nullable();
            $table->string('pin_or_equivalent')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->string('delivery_place')->nullable();
            $table->text('signature')->nullable();
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
        Schema::dropIfExists('procurement_quotations');
    }
};
