<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //if (! $this->app->runningInConsole()) {
        if (\Schema::hasTable(\App\CompetenceProficiencyLevel::getTableName())) {
            // App is not running in CLI context
            // Do HTTP-specific stuff here
            $competenceProficiencyLevels = \App\CompetenceProficiencyLevel::all();
            $globalMinCompetenceProficiencyLevelId = \App\CompetenceProficiencyLevel::min('id');
            $globalMaxCompetenceProficiencyLevelId = \App\CompetenceProficiencyLevel::max('id');
            /*echo "min = $globalMinCompetenceProficiencyLevelId <br>";
            echo "max = $globalMaxCompetenceProficiencyLevelId <br>";*/
            View::share('globalCompetenceProficiencyLevels', $competenceProficiencyLevels);
            View::share('globalMinCompetenceProficiencyLevelId', $globalMinCompetenceProficiencyLevelId);
            View::share('globalMaxCompetenceProficiencyLevelId', $globalMaxCompetenceProficiencyLevelId);
        }
        //Builder::defaultStringLength(191‌​);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
