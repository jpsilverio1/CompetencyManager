<?php

use Illuminate\Database\Seeder;

class CompetencyProficiencyLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $competence = new \App\CompetenceProficiencyLevel;
        $competence->name = "Básico";
        $competence->save();

        $competence = new \App\CompetenceProficiencyLevel;
        $competence->name = "Intermediário";
        $competence->save();

        $competence = new \App\CompetenceProficiencyLevel;
        $competence->name = "Avançado";
        $competence->save();

    }
}
