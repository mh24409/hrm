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
        Schema::create('pm_tasks_list', function (Blueprint $table) {
            $table->id()->comment('This will be used as the task_id, as previously task_id was primary key');
            $table->string('uuid');
            $table->string('project_id')->nullable()->comment('under a project');
            $table->string('sprint_id')->nullable();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->string('project_lead')->nullable()->comment('Reporter of the project');
            $table->string('employee_id')->nullable()->comment('Team members');
            $table->integer('priority')->nullable()->comment('high = 2 or 1 = medium or low = 0');
            $table->text('attachment')->nullable();
            $table->string('task_status')->default(1)->nullable()->comment('to do =1 , in progress = 2 or done = 3');
            $table->integer('is_task')->default(0)->nullable()->comment('if 0 remain in backlogs else show in task');
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
        Schema::dropIfExists('pm_tasks_list');
    }
};
