<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJobroblesCompetenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobroles_competencies', function($table) {
            $table->integer('competence_proficiency_level_id')->unsigned()->default(1);
            $table->foreign('competence_proficiency_level_id')->references('id')->on('competence_proficiency_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobroles_competencies', function($table) {
            $table->dropForeign(['competence_proficiency_level_id']);
            $table->dropColumn('competence_proficiency_level_id');
        });
    }
}
