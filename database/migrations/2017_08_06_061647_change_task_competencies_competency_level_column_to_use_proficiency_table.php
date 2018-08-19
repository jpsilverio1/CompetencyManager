<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTaskCompetenciesCompetencyLevelColumnToUseProficiencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_competencies', function (Blueprint $table) {
            $table->integer('competency_proficiency_level_id')->unsigned()->default(1);
        });

        $results = DB::table('task_competencies')->select('id', 'competency_level')->get();
        foreach($results as $result) {
            $competencyLevel = $result->competency_level;
            $id = $result->id;
            $newValue = $this->getNewValue($competencyLevel);
            if ($newValue > 0) {
                DB::table('task_competencies')
                    ->where('id', $id)
                    ->update(['competency_proficiency_level_id' => $newValue]);
            }
            echo "$id = $competencyLevel - $newValue <br>";

        }

        Schema::table('task_competencies', function (Blueprint $table) {
            $table->foreign('competency_proficiency_level_id')->references('id')->on('competence_proficiency_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_competencies', function (Blueprint $table) {
            $table->dropForeign(['competency_proficiency_level_id']);
            $table->dropColumn('competency_proficiency_level_id');
        });
    }

    public function getNewValue($competencyLevel) {
        if ($competencyLevel == "Básico") {
            return 1;
        }else {
            if ($competencyLevel == "Intermediário") {
                return 2;
            } else {
                if ($competencyLevel == "Avançado") {
                    return 3;
                } else {
                    return -1;
                }
            }
        }
    }
}
