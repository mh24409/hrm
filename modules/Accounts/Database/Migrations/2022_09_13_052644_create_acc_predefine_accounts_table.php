<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_predefine_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('cash_code');
            $table->integer('bank_code');
            $table->integer('advance');
            $table->integer('fixed_asset');
            $table->integer('purchase_code');
            $table->integer('purchase_discount');
            $table->integer('sales_code');
            $table->integer('customer_code');
            $table->integer('supplier_code');
            $table->integer('costs_of_good_solds');
            $table->integer('vat');
            $table->integer('tax');
            $table->integer('inventory_code');
            $table->integer('current_year_profit_loss_code');
            $table->integer('last_year_profit_loss_code');
            $table->integer('salary_code')->nullable();
            $table->integer('employee_salary_expense')->nullable();
            $table->integer('prov_state_tax')->nullable();
            $table->integer('state_tax')->nullable();
            $table->integer('sales_discount')->nullable();
            $table->integer('shipping_cost1')->nullable();
            $table->integer('shipping_cost2')->nullable();
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
        Schema::dropIfExists('acc_predefine_accounts');
    }
};
