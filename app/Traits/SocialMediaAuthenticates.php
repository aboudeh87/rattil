<?php

namespace App\Traits;


use App\User;
use App\SocialMedia;
use Laravel\Socialite\Contracts\User as SocialAccount;

/**
 * Class SocialMediaAuthenticates
 */
trait SocialMediaAuthenticates
{

    /**
     * The name of social media provider
     *
     * @var string
     */
    protected $provider;

    /**
     * The return social account user
     *
     * @var SocialAccount
     */
    protected $account;

    /**
     * The user model that connected to the social media profile
     *
     * @var User
     */
    protected $user;

    /**
     * is the action was register new user
     *
     * @var bool
     */
    protected $registered = false;

    /**
     * is the action connect the account to existing user
     *
     * @var bool
     */
    protected $connected = false;

    /**
     * @var null
     */
    protected $guard = null;

    /**
     * Login the user that related to the social account
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function loginSocialAccount()
    {
        if (!$this->user instanceof User)
        {
            $this->user = $this->findUserBySocialMedia();
        }

        $this->guard($this->guard)->login($this->user, true);

        return $this->respondLoginSuccess();
    }

    /**
     * Connect the social account to an existing user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function connectSocialAccount()
    {
        if (!$this->user instanceof User)
        {
            $this->user = $this->findUserByEmail();
        }

        return $this->createSocialProfile()
            ->loginSocialAccount();
    }

    /**
     * Register new user by social media account
     */
    protected function registerNewUser()
    {
        $this->registered = true;

        return $this->createUser()
            ->createSocialProfile()
            ->loginSocialAccount();
    }

    /**
     * Get the User model from that related to the social account
     *
     * @return User
     */
    protected function findUserBySocialMedia()
    {
        return SocialMedia::whereProvider($this->provider)
            ->whereSocialId($this->account->getId())
            ->first()
            ->user;
    }

    /**
     * Get the User model from that related to the social account
     *
     * @return \App\User
     */
    protected function findUserByEmail()
    {
        return User::whereEmail($this->account->getEmail())->first();
    }

    /**
     * Create a new user from social account
     *
     * @return $this
     */
    protected function createUser()
    {
        $this->user = User::create([
            'name'     => $this->account->getName(),
            'email'    => $this->account->getEmail() ?: null,
            'avatar'   => $this->account->getAvatar() ?: null,
            'password' => bcrypt(str_random(10)),
        ]);

        return $this;
    }

    /**
     * Create a new Social profile
     *
     * @return $this
     */
    protected function createSocialProfile()
    {
        $this->user->socials()->create([
            'social_id' => $this->account->getId(),
            'provider'  => $this->provider,
            'token'     => $this->account->token,
            'nickname'  => $this->account->getNickname() ?: null,
            'email'     => $this->account->getEmail() ?: null,
            'avatar'    => $this->account->getAvatar() ?: null,
        ]);

        return $this;
    }

    /**
     * Return login successfully response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    abstract protected function respondLoginSuccess();

    /**
     * Check if the social media account exists
     *
     * @return bool
     */
    protected function checkIfSocialAccountExist()
    {
        return !!SocialMedia::whereProvider($this->provider)
            ->whereSocialId($this->account->getId())
            ->count();
    }

    /**
     * Check if the email of social media account exists
     *
     * @param $email
     *
     * @return bool
     */
    protected function checkIfEmailExists($email)
    {
        return $email && User::whereEmail($email)->count();
    }

    /**
     * return the redirect URL of social media callback
     *
     * @param $provider
     */
    public function registerRedirectUrl($provider)
    {
        \Config::set(
            "services.{$provider}.redirect",
            url()->action('Auth\SocialMediaController@handleProviderCallback', [$provider])
        );
    }

    /**
     * Get application user guard
     *
     * @param null $guard
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard($guard = null)
    {
        return \Auth::guard($guard);
    }

}