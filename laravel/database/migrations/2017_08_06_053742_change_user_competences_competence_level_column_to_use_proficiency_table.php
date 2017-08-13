<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserCompetencesCompetenceLevelColumnToUseProficiencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // do the following steps in order:
    // 1- add a new column with the desired data type to the table
    // 2- fill the new column with the appropriate data
    public function up()
    {
        Schema::table('user_competences', function (Blueprint $table) {
            $table->integer('competence_proficiency_level_id')->unsigned()->default(1);
        });

        $results = DB::table('user_competences')->select('id', 'competence_level')->get();
        foreach($results as $result) {
            $competenceLevel = $result->competence_level;
            $id = $result->id;
            $newValue = $this->getNewValue($competenceLevel);
            if ($newValue > 0) {
                DB::table('user_competences')
                    ->where('id', $id)
                    ->update(['competence_proficiency_level_id' => $newValue]);
            }
            echo "$id = $competenceLevel - $newValue <br>";

        }
        Schema::table('user_competences', function (Blueprint $table) {
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
        Schema::table('user_competences', function (Blueprint $table) {
            $table->dropForeign(['competence_proficiency_level_id']);
            $table->dropColumn('competence_proficiency_level_id');
        });

    }

    public function getNewValue($competenceLevel) {
        if ($competenceLevel == "Básico") {
            return 1;
        }else {
            if ($competenceLevel == "Intermediário") {
                return 2;
            } else {
                if ($competenceLevel == "Avançado") {
                    return 3;
                } else {
                    return -1;
                }
            }
        }
    }
}
