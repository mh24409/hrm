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
        Schema::create('acc_coas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('account_code')->nullable();
            $table->string('account_name');
            $table->unsignedBigInteger('head_level');
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('acc_type_id');
            $table->boolean('is_cash_nature')->default(0);
            $table->boolean('is_bank_nature')->default(0);
            $table->boolean('is_budget')->default(0);
            $table->boolean('is_depreciation')->default(0);
            $table->integer('depreciation_rate')->nullable();
            $table->boolean('is_subtype')->default(0);
            $table->unsignedBigInteger('subtype_id')->nullable();
            $table->boolean('is_stock')->default(0);
            $table->boolean('is_fixed_asset_schedule')->default(0);
            $table->string('note_no')->nullable();
            $table->string('asset_code')->nullable();
            $table->string('dep_code')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('acc_coas');
    }
};
