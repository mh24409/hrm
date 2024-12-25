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
        Schema::create('procurement_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->comment('id of request, quotation, bid analysis, purchase order, or goods received form');
            $table->integer('item_type')->default(1)->comment('type 1 = request, 2 = quote, 3 = bid, 4 = purchase order, 5 == goods received');
            $table->string('company')->nullable();
            $table->string('material_description')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->double('quantity')->default(0);
            $table->double('unit_price')->default(0);
            $table->double('total_price')->default(0);
            $table->string('choosing_reason')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('procurement_request_items');
    }
};
