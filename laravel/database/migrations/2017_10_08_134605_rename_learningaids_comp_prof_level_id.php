<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLearningaidsCompProfLevelId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningaids_competencies', function (Blueprint $table) {
            $table->renameColumn('comp_prof_level_id', 'competence_proficiency_level_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learningaids_competencies', function (Blueprint $table) {
            $table->renameColumn('competence_proficiency_level_id', 'comp_prof_level_id');

        });
    }
}
