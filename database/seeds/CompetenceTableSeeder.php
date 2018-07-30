<?php

use Illuminate\Database\Seeder;

class CompetenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(file('./resources/assets/seeds/all_linkedIn_skills.txt') as $competenceName) {
            $description = "Descrição da competência: $competenceName";
            $competence = new \App\Competency;
            $competence->name = trim($competenceName);
            $competence->description = trim($description);
            $competence->save();
        }
        if (\App\Competency::isBroken()) {
            \App\CompetencyCompetency::fixTree();
            echo "consertar";
        }
    }
}
