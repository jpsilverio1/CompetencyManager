<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUserEndorsementsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_endorsements', function (Blueprint $table) {
            $table->renameColumn('competency_id', 'competence_id');
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
            $table->renameColumn('competence_id', 'competency_id');
        });
    }
}
