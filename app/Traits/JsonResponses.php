<?php

namespace App\Traits;


/**
 * Class JsonResponses
 *
 * @package App\Traits
 */
trait JsonResponses
{

    /**
     * Return Access denied response
     *
     * @param null|string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function accessDeniedResponse($message = null)
    {
        return $this->respondError($message ?: trans('messages.privacy_access_denied'), 403);
    }
}