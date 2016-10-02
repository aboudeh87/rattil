<?php

namespace App\Traits;


use Illuminate\Support\Str;
use Illuminate\Http\Request;

/**
 * Class ApiLoginUser
 */
trait ApiLoginUser
{

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed                    $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $this->createAnApiToken($user);

        return $this->respond([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
                'token'    => $user->api_token,
            ],
        ]);
    }

    /**
     * Create a new API token for user
     *
     * @param $user
     */
    protected function createAnApiToken(&$user)
    {
        $user->api_token = Str::random(60);
        $user->save();
    }

}