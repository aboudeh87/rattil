<?php

namespace App\Http\Controllers\Auth;


use Socialite;
use App\Http\Controllers\Controller;
use App\Traits\SocialMediaAuthenticates;
use Illuminate\Foundation\Auth\RedirectsUsers;

/**
 * Class SocialMediaController
 */
class SocialMediaController extends Controller
{

    use SocialMediaAuthenticates, RedirectsUsers;

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param string $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        $this->registerRedirectUrl($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param string $provider
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleProviderCallback($provider)
    {
        $this->registerRedirectUrl($provider);

        $this->account = Socialite::driver($provider)->user();
        $this->provider = $provider;

        return $this->socialAccountProcess();
    }

    /**
     * Return login successfully response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondLoginSuccess()
    {
        return redirect($this->getRedirectUrl());
    }
}