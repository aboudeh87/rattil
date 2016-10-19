<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Recitation;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;

class FavoritesController extends ApiController
{

    use ProfilesChecker, JsonResponses;

    /**
     * Favorite a recitation
     *
     * @param Recitation $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorite(Recitation $model)
    {
        if ($this->checkIfRecitationDisabled($model))
        {
            return $this->respondError(trans('messages.recitation_removed'));
        }

        /** @var User $user */
        $user = auth($this->guard)->user();

        if (!$model->favorators()->where('user_id', $user->id)->count())
        {
            $model->favorators()->create(['user_id' => $user->id]);
        }

        return $this->favoriteAddedSuccess();
    }

    /**
     * Un-Favorite a recitation
     *
     * @param Recitation $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfavorite(Recitation $model)
    {
        if ($this->checkIfRecitationDisabled($model))
        {
            return $this->respondError(trans('messages.recitation_removed'));
        }

        /** @var User $user */
        $user = auth($this->guard)->user();

        $model->favorators()->where(['user_id' => $user->id])->delete();

        return $this->favoriteRemovedSuccess();
    }

    /**
     * Return success response after favorite a recitation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function favoriteAddedSuccess()
    {
        return $this->respondSuccess(trans('messages.favorite_success'));
    }

    /**
     * Return success response after remove recitation from favorites
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function favoriteRemovedSuccess()
    {
        return $this->respondSuccess(trans('messages.unfavorite_success'));
    }
}