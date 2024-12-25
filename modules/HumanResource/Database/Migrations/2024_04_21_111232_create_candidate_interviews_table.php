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
        Schema::create('candidate_interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('interviewer');
            $table->unsignedBigInteger('position_id');
            $table->date('interview_date');
            $table->double('interview_marks');
            $table->double('written_marks')->nullable();
            $table->double('mcq_marks')->nullable();
            $table->double('total_marks')->nullable();
            $table->string('recommandation')->nullable();
            $table->boolean('selection')->default(0);
            $table->text('details')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_interviews');
    }
};
