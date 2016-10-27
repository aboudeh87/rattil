<?php

namespace App\Traits;


use Auth;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Mail\ActivationMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RedirectsUsers;

/**
 * Class RegistersUsers
 */
trait RegistersUsers
{

    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        \Mail::to($user->email)->send(New ActivationMail($user));

        return $this->registeredSuccessResponse();
    }

    /**
     * The response after the user registered success
     *
     * @return \Illuminate\Http\Response
     */
    protected function registeredSuccessResponse()
    {
        return redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @param string|null $guard
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard($guard = null)
    {
        return Auth::guard($guard);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'username' => 'required|regex:/[a-zA_Z][a-zA-Z0-9_.]+/|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'             => $data['name'],
            'username'         => $data['username'] ?: null,
            'email'            => $data['email'] ?: null,
            'password'         => bcrypt($data['password']),
            'activation_token' => str_random(60),
        ]);
    }
}