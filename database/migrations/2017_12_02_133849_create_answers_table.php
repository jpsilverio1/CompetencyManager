<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('personal_competence_id')->unsigned();
            $table->integer('task_id')->unsigned();
			$table->integer('judge_user_id')->unsigned();
            $table->integer('evaluated_user_id')->unsigned();
            $table->integer('personal_competence_level_id')->unsigned();
			$table->timestamps();
            $table->foreign('personal_competence_id')->references('id')->on('personal_competencies');
            $table->foreign('task_id')->references('id')->on('tasks');
			$table->foreign('judge_user_id')->references('id')->on('users');
            $table->foreign('evaluated_user_id')->references('id')->on('users');
            $table->foreign('personal_competence_level_id', 'personal_competence_level_answers_fk')->references('id')->on('personal_competence_proficiency_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
