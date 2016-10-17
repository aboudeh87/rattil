<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Transformers\V1\ProfileTransformer;

class ProfileController extends ApiController
{

    /**
     * @var User
     */
    protected $model;

    /**
     * Return items of model
     *
     * @param null|string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if (!$this->isAllowed($id))
        {
            return $this->handleAccessDeniedResponse();
        }

        return $this->returnProfileResponse();
    }

    /**
     * Check if the logged in user can access profile
     *
     * @param string $value
     *
     * @return bool
     */
    protected function isAllowed($value = null)
    {
        if ($value === null)
        {
            $this->model = auth()->user();

            return true;
        }

        $this->model = $this->getUserModel($value);
        $privacy = $this->model->properties()->whereKey('private')->first();

        if ($this->model->id === auth()->id() || !$privacy || !$privacy->value)
        {
            return true;
        }

        return (bool) $this->model
            ->followers()
            ->where('user_id', auth()->id())
            ->count();
    }

    /**
     * Get user model from username or ID
     *
     * @param string $value
     *
     * @return User
     */
    protected function getUserModel($value)
    {
        if (is_numeric($value))
        {
            return User::whereId($value)->firstOrFail();
        }
        else
        {
            return User::whereUsername($value)->firstOrFail();
        }
    }

    /**
     * Return Access denied response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleAccessDeniedResponse()
    {
        return $this->respondError(trans('messages.privacy_access_denied'), 403);
    }

    /**
     * return profile of user response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnProfileResponse()
    {
        return $this->respond((new ProfileTransformer)->transform($this->model));
    }
}