<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCompetenceLevelColumnFromUserEndorsementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_endorsements', function (Blueprint $table) {
            $table->dropColumn('competence_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_endorsements', function (Blueprint $table) {
            $table->string('competence_level');
        });
    }
}
