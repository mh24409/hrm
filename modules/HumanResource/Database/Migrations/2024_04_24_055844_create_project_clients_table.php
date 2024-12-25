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
        Schema::create('pm_clients', function (Blueprint $table) {
            $table->id()->comment('This will be used as the client_id, as previously client_id was primary key');
            $table->string('uuid');
            $table->string('company_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
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
        Schema::dropIfExists('pm_clients');
    }
};
