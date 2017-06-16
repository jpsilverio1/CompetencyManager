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
       // echo base_path();
        foreach(file('./resources/assets/seeds/all_linkedIn_skills.txt') as $competenceName) {
            // loop with $line for each line of yourfile.txt

            $description = "Descrição da competência: $competenceName";
            $competence = new \App\Competency;
            $competence->name = $competenceName;
            $competence->description = $description;
            $competence->save();
            echo "line $competenceName -  $description<br>";
        }
    }
}
