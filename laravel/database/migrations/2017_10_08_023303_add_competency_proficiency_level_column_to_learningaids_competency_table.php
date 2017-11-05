<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompetencyProficiencyLevelColumnToLearningaidsCompetencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('learningaids_competencies', function($table) {
            $table->integer('comp_prof_level_id')->unsigned()->default(1);
            $table->foreign('comp_prof_level_id')->references('id')->on('competence_proficiency_level');
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
        Schema::table('learningaids_competencies', function($table) {
            $table->dropForeign(['comp_prof_level_id']);
            $table->dropColumn('comp_prof_level_id');
        });
    }
}
