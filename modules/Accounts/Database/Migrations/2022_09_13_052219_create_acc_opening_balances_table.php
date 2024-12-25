<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('acc_opening_balances', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('acc_coa_id');
            $table->unsignedBigInteger('acc_subtype_id')->nullable();
            $table->unsignedBigInteger('acc_subcode_id')->nullable();
            $table->double('debit')->nullable();
            $table->double('credit')->nullable();
            $table->date('open_date');
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
    public function down() {
        Schema::dropIfExists('acc_opening_balances');
    }
};
