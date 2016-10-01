<?php

namespace App\Traits;


use Illuminate\Http\Request;
use \Illuminate\Foundation\Auth\AuthenticatesUsers as BaseAuthenticatesUsers;

/**
 * Class AuthenticatesUsers
 */
trait AuthenticatesUsers
{

    use BaseAuthenticatesUsers;

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

}