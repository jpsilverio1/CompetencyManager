<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningaidsCompetenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('learning_aids_competencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competency_id')->unsigned();
            $table->integer('learning_aid_id')->unsigned();
            $table->integer('competency_proficiency_level_id')->unsigned()->default(1);
            $table->foreign('competency_id')->references('id')->on('competencies');
            $table->foreign('learning_aid_id')->references('id')->on('learning_aids');
            $table->foreign('competency_proficiency_level_id', 'learning_aid_comps_comp_prof_lvl_id_foreign')->references('id')->on('competence_proficiency_level');

            //$table->primary(['competency_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('learning_aids_competencies');
    }
}
