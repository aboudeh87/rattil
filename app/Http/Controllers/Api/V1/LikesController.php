<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Recitation;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use App\Transformers\V1\RecitationTransformer;

class LikesController extends ApiController
{

    use ProfilesChecker, JsonResponses;

    /**
     * Like a recitation
     *
     * @param Recitation $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Recitation $model)
    {
        if ($this->checkIfRecitationDisabled($model))
        {
            return $this->respondError(trans('messages.recitation_removed'));
        }

        /** @var User $user */
        $user = auth($this->guard)->user();

        if (!$model->likes()->where('user_id', $user->id)->count())
        {
            $model->likes()->create(['user_id' => $user->id]);
        }

        return $this->likedSuccess();
    }

    /**
     * Un-Like a recitation
     *
     * @param Recitation $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlike(Recitation $model)
    {
        if ($this->checkIfRecitationDisabled($model))
        {
            return $this->respondError(trans('messages.recitation_removed'));
        }

        /** @var User $user */
        $user = auth($this->guard)->user();

        $like = $model->likes()->where(['user_id' => $user->id])->first();

        if ($like)
        {
            $like->save();
            $like->dalete();
        }

        return $this->unlikeSuccess();
    }

    /**
     * Return success response after like a recitation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function likedSuccess()
    {
        return $this->respondSuccess(trans('messages.like_success'));
    }

    /**
     * Return success response after un-like a recitation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unlikeSuccess()
    {
        return $this->respondSuccess(trans('messages.unlike_success'));
    }
}