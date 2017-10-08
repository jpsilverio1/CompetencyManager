<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLearningaidsIdAtlearningaidsCompetenciesAndLearningaidsUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningaids_competencies', function (Blueprint $table) {
            $table->renameColumn('learningaids_id', 'learningaid_id');

        });
        Schema::table('learningaids_user', function (Blueprint $table) {
            $table->renameColumn('learningaids_id', 'learningaid_id');

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
            $table->renameColumn('learningaid_id', 'learningaids_id');

        });
        Schema::table('learningaids_user', function (Blueprint $table) {
            $table->renameColumn('learningaid_id', 'learningaids_id');

        });
    }
}
