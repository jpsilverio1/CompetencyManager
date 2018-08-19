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
        $competenceProficiencyLevels = \App\CompetenceProficiencyLevel::all();
        $globalMinConpetenceProficiencyLevelId = \App\CompetenceProficiencyLevel::min('id');
        $globalMaxConpetenceProficiencyLevelId = \App\CompetenceProficiencyLevel::max('id');
        View::share('globalCompetenceProficiencyLevels', $competenceProficiencyLevels);
        View::share('globalMinConpetenceProficiencyLevelId', $globalMinConpetenceProficiencyLevelId);
        View::share('globalMaxConpetenceProficiencyLevelId', $globalMaxConpetenceProficiencyLevelId);
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
