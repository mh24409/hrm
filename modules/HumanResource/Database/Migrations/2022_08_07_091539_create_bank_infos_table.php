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
        Schema::create('bank_infos', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('employee_id');
            $table->string('account_name')->nullable();
            $table->string('acc_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('route_number')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('bban_num')->nullable();
            $table->text('branch_address')->nullable();
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
        Schema::dropIfExists('bank_infos');
    }
};
