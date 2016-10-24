<?php

namespace App\Http\Controllers\Auth;


use App\User;
use App\Traits\RegistersUsers;
use App\Http\Controllers\Controller;

/**
 * Class RegisterController
 */
class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Activate user account
     *
     * @param $token
     *
     * @return \Illuminate\Http\Response
     */
    public function activate($token)
    {
        if (!$user = User::whereActivationToken($token)->first())
        {
            return view('auth.activation.invalid');
        }

        $user->activation_token = null;
        $user->save();

        return view('auth.activation.success');
    }
}
