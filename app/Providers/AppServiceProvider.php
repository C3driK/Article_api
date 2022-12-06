<?php

namespace App\Providers;

use App\Services\CanAnalyzeSentiment;
use App\Services\SentimAnalyzer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CanAnalyzeSentiment::class, SentimAnalyzer::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
