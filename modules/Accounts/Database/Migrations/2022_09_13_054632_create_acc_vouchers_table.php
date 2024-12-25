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
        Schema::create('acc_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('acc_coa_id');
            $table->foreignId('financial_year_id');
            $table->foreignId('acc_subtype_id')->nullable();
            $table->foreignId('acc_subcode_id')->nullable();
            $table->string('voucher_no')->nullable();
            $table->foreignId('voucher_type')->nullable();
            $table->string('reference_no')->nullable();
            $table->date('voucher_date')->nullable();
            $table->tinyText('narration')->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->boolean('is_honour')->default(0);
            $table->tinyText('ledger_comment')->nullable();
            $table->double('debit',18,2)->nullable();
            $table->double('credit',18,2)->nullable();
            $table->unsignedBigInteger('reverse_code')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->updateCreatedBy();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('acc_vouchers');
    }
};
