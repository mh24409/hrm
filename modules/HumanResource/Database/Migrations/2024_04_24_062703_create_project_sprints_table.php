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
        Schema::create('pm_sprints', function (Blueprint $table) {
            $table->id()->comment('This will be used as the sprint_id, as previously sprint_id was primary key');
            $table->string('uuid');
            $table->string('project_id')->nullable()->comment('under a project');
            $table->string('sprint_name')->nullable();
            $table->string('duration')->nullable()->comment('in days');
            $table->date('start_date')->nullable();
            $table->date('close_date')->nullable();
            $table->text('sprint_goal')->nullable();
            $table->integer('is_finished')->default(0);
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
        Schema::dropIfExists('pm_sprints');
    }
};
