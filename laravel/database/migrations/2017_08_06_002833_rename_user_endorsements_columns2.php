<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUserEndorsementsColumns2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_endorsements', function (Blueprint $table) {
            $table->renameColumn('competency_level', 'competence_level');
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
            $table->renameColumn('competence_level', 'competency_level');
        });
    }
}
