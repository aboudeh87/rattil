<?php

namespace App\Http\Controllers\Api\Auth\V1;


use Auth;
use Illuminate\Http\Request;
use App\Traits\ApiLoginUser;
use App\Traits\AuthenticatesUsers;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\Api\V1\ApiController;

/**
 * Class LoginController
 */
class LoginController extends ApiController
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ApiLoginUser
    {
        ApiLoginUser::authenticated insteadof AuthenticatesUsers;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api', ['except' => 'logout']);
        $this->middleware('auth:api', ['only' => 'logout']);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user());
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->clearApiToken($this->guard('api')->user());

        return $this->respondSuccess('تم تسجيل الخروج بنجاح.');
    }

    /**
     * Clear the API token of user after logout
     *
     * @param $user
     */
    protected function clearApiToken($user)
    {
        $user->api_token = null;
        $user->save();
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);

        return $this->respondError($message);
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return $this->respondError(Lang::get('auth.failed'), 422);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @param string|null $guard
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard($guard = null)
    {
        return Auth::guard($guard);
    }

}
