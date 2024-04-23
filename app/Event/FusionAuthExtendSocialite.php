<?php

namespace App\Event;

use App\Providers\FusionAuthProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
class FusionAuthExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('fusionauth', FusionAuthProvider::class);
    }
}
