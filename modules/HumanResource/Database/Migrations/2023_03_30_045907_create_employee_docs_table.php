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
        Schema::create('employee_docs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('doc_title');
            $table->unsignedBigInteger('employee_id');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('issue_date')->nullable();
            $table->boolean('is_returned')->default(0);
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
        Schema::dropIfExists('employee_docs');
    }
};
