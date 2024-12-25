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
        // Rename existing table
        Schema::rename('project_management', 'pm_projects');

        // Extend the table with additional columns
        Schema::table('pm_projects', function (Blueprint $table) {
            $table->string('id')->comment('This will be used as the project_id, as previously project_is was separate column')->change();
            $table->string('uuid');
            $table->integer('first_parent_project_id')->default(0)->nullable()
            ->comment('if create any new version of existing project. it will always remain the first parent id.');
            $table->integer('second_parent_project_id')->default(0)->nullable()->comment('it will use for backlogs task transfer.');
            $table->string('version_no')->default(1)->nullable()
            ->comment('It will increment always, after creating new version, otherwise always 1');
            $table->string('project_name')->nullable();
            $table->string('client_id')->nullable();
            $table->string('project_lead')->nullable();
            $table->string('approximate_tasks')->nullable();
            $table->string('complete_tasks')->nullable();
            $table->date('start_date')->nullable()->comment('when the first sprint is started of any project');
            $table->date('project_start_date')->nullable()->comment('On project creation, this date will be defined');
            $table->date('close_date')->nullable()->comment('when project is being closed from project update.');
            $table->string('project_duration')->nullable();
            $table->string('completed_days')->nullable()->comment('days passed from start date of the project.');
            $table->text('project_summary')->nullable();
            $table->string('is_completed')->default(0)->nullable()->comment('can complete forcefully or manually be completed');
            $table->string('project_reward_point')->default(0)->comment('this point will be given to all the employee of this project');
            $table->updateCreatedBy();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
