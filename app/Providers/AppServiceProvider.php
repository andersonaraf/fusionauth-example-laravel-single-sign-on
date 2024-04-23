<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
//        $this->app['events']->listen(SocialiteWasCalled::class, function (SocialiteWasCalled $socialiteWasCalled) {
////            $socialiteWasCalled->extendSocialite('fusionauth', FusionAuthProvider::class);
//        });
    }
}
