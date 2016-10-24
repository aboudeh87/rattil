<?php

namespace App\Traits;


use App\User;
use Illuminate\Http\Request;
use \Illuminate\Foundation\Auth\AuthenticatesUsers as BaseAuthenticatesUsers;

/**
 * Class AuthenticatesUsers
 */
trait AuthenticatesUsers
{

    use BaseAuthenticatesUsers;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request))
        {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if ($this->guard()->attempt($credentials, $request->has('remember'), false))
        {
            /** @var User $user */
            $user = $this->guard()->getLastAttempted();

            if ($user->activation_token)
            {
                return $this->sendShouldActivateResponse();
            }

            $this->guard()->login($user, $request->has('remember'));

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        $username = $request->get('username');
        $field = strpos($username, '@') ? 'email' : 'username';

        return [$field => $username, 'password' => $request->get('password')];
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
    }

    /**
     * Return error response when the account not activated
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendShouldActivateResponse()
    {
        return redirect()->back()->withErrors(trans('messages.account_not_activated'));
    }
}