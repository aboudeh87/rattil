<?php

namespace App\Http\Controllers\Api\Auth\V1;


use App\Traits\RegistersUsers;
use App\Http\Controllers\Api\V1\ApiController;

/**
 * Class RegisterController
 */
class RegisterController extends ApiController
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    /**
     * The response after the user registered success
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function registeredSuccessResponse()
    {
        return $this->respondSuccess('لقد تم تسجيلك بنجاح');
    }
}
