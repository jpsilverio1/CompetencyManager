<?php

use Illuminate\Database\Seeder;

class PersonalCompetenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$questions = file('./resources/assets/seeds/questions.txt');
		
		
		$descriptions = file('./resources/assets/seeds/personal_competencies_descriptions.txt');
		$names = file('./resources/assets/seeds/personal_competencies_names.txt');
		
		for ($i = 0; $i < sizeof($questions); $i++) {
			$question = new \App\Question;
			$question->description = $questions[$i];
			$question->save();
			
			$personalCompetence = new \App\PersonalCompetence;
			$personalCompetence->name = $names[$i];
			$personalCompetence->description = $descriptions[$i];
			$personalCompetence->question_id = $question->id;
			$personalCompetence->save();
		}
		
		$proficiencyLevels = file('./resources/assets/seeds/personal_competencies_proficiency_level.txt');
		
		for ($j = 0; $j < sizeof($proficiencyLevels); $j++) {
			$proficiencyLevel = new \App\PersonalCompetenceProficiencyLevel;
			$proficiencyLevel->name = $proficiencyLevels[$j];
			$proficiencyLevel->save();
		}
		
    }
}
