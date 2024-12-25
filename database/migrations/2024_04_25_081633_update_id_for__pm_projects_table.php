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
        Schema::table('pm_projects', function (Blueprint $table) {
            // Set id as auto-increment
            $table->bigIncrements('id')->comment('This will be used as the project_id, as previously project_is was separate column')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_projects', function (Blueprint $table) {
            // Rollback the change if necessary
            $table->string('id')->comment('This will be used as the project_id, as previously project_is was separate column')->change();
        });
    }
};
