<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToUsercompetenciesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_competences', function($table) {
            $table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_competences', function($table) {
            $table->dropColumn('created_at');
			$table->dropColumn('updated_at');
        });
    }
}
