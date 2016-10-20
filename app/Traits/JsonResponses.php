<?php

namespace App\Traits;


use App\Recitation;

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
        return $this->respondError($message ?: trans('messages.privacy_access_denied', ['module' => 'profile']), 403);
    }

    /**
     * Check if recitation disabled
     *
     * @param \App\Recitation $model
     *
     * @return bool
     */
    protected function checkIfRecitationDisabled(Recitation $model)
    {
        return (bool) $model->disabled;
    }
}