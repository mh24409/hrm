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
        Schema::create('acc_monthly_balances', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('acc_coa_id');
            $table->double('balance1');
            $table->double('balance2');
            $table->double('balance3');
            $table->double('balance4');
            $table->double('balance5');
            $table->double('balance6');
            $table->double('balance7');
            $table->double('balance8');
            $table->double('balance9');
            $table->double('balance10');
            $table->double('balance11');
            $table->double('balance12');
            $table->double('total_balance');
            $table->timestamp('updated_date');
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
        Schema::dropIfExists('acc_monthly_balances');
    }
};
