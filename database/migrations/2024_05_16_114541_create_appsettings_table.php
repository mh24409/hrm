<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appsettings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('acceptablerange')->nullable();
            $table->text('googleapi_authkey')->nullable();
            $table->updateCreatedBy();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appsettings');
    }
};
