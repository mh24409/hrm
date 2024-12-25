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
        Schema::create('point_collaboratives', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('point_shared_by')->nullable()->comment('Employee shared point');
            $table->string('point_shared_with')->nullable()->comment('Employee received point');
            $table->text('reason')->nullable();
            $table->string('point')->nullable();
            $table->date('point_date')->nullable();
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
        Schema::dropIfExists('point_collaboratives');
    }
};
