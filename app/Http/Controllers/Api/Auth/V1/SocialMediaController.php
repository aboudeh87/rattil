<?php

namespace App\Http\Controllers\Api\Auth\V1;


use Socialite;
use App\Traits\ApiLoginUser;
use Illuminate\Http\Request;
use App\Traits\SocialMediaAuthenticates;
use App\Http\Controllers\Api\V1\ApiController;

/**
 * Class SocialMediaController
 */
class SocialMediaController extends ApiController
{

    use SocialMediaAuthenticates, ApiLoginUser;

    /**
     * Obtain the user information from GitHub.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $provider
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $this->validate($request, ['oauth_token' => 'required']);

        $this->registerRedirectUrl($provider);

        $this->provider = $provider;
        $this->account = Socialite::driver($provider)->userFromToken($request->get('oauth_token'));

        return $this->socialAccountProcess();
    }

    /**
     * Return login successfully response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondLoginSuccess()
    {
        return $this->authenticated(app('request'), $this->user);
    }
}