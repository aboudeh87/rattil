<?php

namespace App\Http\Controllers\Api\Auth\V1;


use Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Class ForgotPasswordController
 */
class ForgotPasswordController extends ApiController
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT)
        {

            return $this->respondSuccess(trans($response));
        }

        // If an error was returned by the password broker, we will get this message
        // translated so we can notify a user of the problem. We'll redirect back
        // to where the users came from so they can attempt this process again.
        return $this->respondError(trans($response));
    }

}
