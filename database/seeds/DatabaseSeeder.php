<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //$this->call(CompetenceTableSeeder::class);
		 $this->call(PersonalCompetenciesTableSeeder::class);
		 $this->call(CompetencyProficiencyLevelSeeder::class);
		 $this->call(WebsiteSeederForTestingPhase::class);
         $this->call(BasicStatisticsSeeder::class);
    }
}
