<?php

namespace App\Http\Controllers\Api\V1;


use App\Follow;
use App\User;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use App\Transformers\V1\FollowerTransformer;
use App\Transformers\V1\PendingRequestTransformer;

class FollowersController extends ApiController
{

    use ProfilesChecker, JsonResponses;

    /**
     * Follow an user
     *
     * @param null $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow($model)
    {
        $model = $this->getUserModel($model);

        /** @var User $user */
        $user = auth($this->guard)->user();
        $private = false;

        if (
        !$user->following()
            ->where(['followable_type' => User::class, 'followable_id' => $model->id])
            ->count()
        )
        {
            $private = $model->properties()->whereKey('private')->first();
            $private = $private && $private->value ? true : false;

            $user->following()->create([
                'followable_type' => User::class,
                'followable_id'   => $model->id,
                'accepted'        => $private ? false : true,
            ]);
        }

        return $this->handleFollowSuccessResponse($model, $private);
    }

    /**
     * Un-Follow an user
     *
     * @param null $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollow($model)
    {
        $model = $this->getUserModel($model);

        /** @var User $user */
        $user = auth($this->guard)->user();

        $follow = $user->following()
            ->where([
                'followable_type' => User::class,
                'followable_id'   => $model->id,
            ])->first();

        if ($follow)
        {
            $follow->save();
            $follow->delete();
        }

        return $this->handleUnFollowSuccessResponse($model);
    }

    /**
     * Delete a follower user for current user
     *
     * @param $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFollower($model)
    {
        $model = $this->getUserModel($model);

        /** @var User $user */
        $user = auth($this->guard)->user();

        $follow = $model->following()
            ->where([
                'followable_type' => User::class,
                'followable_id'   => $user->id,
            ])->first();

        if ($follow)
        {
            $follow->save();
            $follow->delete();
        }

        return $this->handleDeleteFollowerSuccessResponse();
    }

    /**
     * Return the list of pending following requests
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pending()
    {
        /** @var User $user */
        $user = auth($this->guard)->user();

        return $this->respondWithPagination(
            $user->followers()
                ->with('user')
                ->whereAccepted(false)
                ->orderBy('created_at', 'desc')
                ->paginate(),
            new PendingRequestTransformer
        );
    }

    /**
     * Accept a following request
     *
     * @param int $userId
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept($userId, $id)
    {
        /** @var User $user */
        $user = auth($this->guard)->user();

        $follow = $user->followers()->whereId($id)->first();

        if (!$follow)
        {
            return $this->respondError(trans('messages.invalid_request_id'), 404);
        }

        if ($follow->accepted)
        {
            return $this->respondError(trans('messages.already_accepted'));
        }

        $follow->accepted = true;
        $follow->save();

        return $this->respondSuccess(trans('messages.following_request_accepted'));
    }

    /**
     * Decline a following request
     *
     * @param int $userId
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function decline($userId, $id)
    {
        /** @var User $user */
        $user = auth($this->guard)->user();

        $follow = $user->followers()->whereId($id)->whereAccepted(false)->first();

        if (!$follow)
        {
            return $this->respondError(trans('messages.invalid_request_id'), 404);
        }

        $follow->save();
        $follow->delete();

        return $this->respondSuccess(trans('messages.following_request_rejected'));
    }

    /**
     * Return The following users of a specific user
     *
     * @param null|string $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function following($model = null)
    {
        if (!$this->isAllowed($model))
        {
            return $this->accessDeniedResponse();
        }

        return $this->respondWithPagination(
            User::withCount([
                'recitations' => function ($query)
                {
                    $query->whereDisabled(false);
                },
            ])->whereIn(
                'id',
                $this->model
                    ->following()
                    ->where([
                        'followable_type' => User::class,
                        'accepted'        => true,
                    ])
                    ->get()
                    ->pluck('followable_id')
                    ->toArray()
            )->paginate(),
            new FollowerTransformer
        );
    }

    /**
     * Return The followers users of a specific user
     *
     * @param null|string $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function followers($model = null)
    {
        if (!$this->isAllowed($model))
        {
            return $this->accessDeniedResponse();
        }

        return $this->respondWithPagination(
            User::withCount([
                'recitations' => function ($query)
                {
                    $query->whereDisabled(false);
                },
            ])->whereIn(
                'id',
                $this->model
                    ->followers()
                    ->where('accepted', true)
                    ->get()
                    ->pluck('user_id')
                    ->toArray()
            )->paginate(),
            new FollowerTransformer
        );
    }

    /**
     * return success response after following an user
     *
     * @param \App\User $model
     * @param bool      $private
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleFollowSuccessResponse(User $model, $private)
    {
        return $this->respondSuccess(
            !$private ? trans('messages.user_followed_success', ['name' => $model->name]) :
                trans('messages.pending_acceptance')
        );
    }

    /**
     * return success response after Un-following an user
     *
     * @param \App\User $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleUnFollowSuccessResponse(User $model)
    {
        return $this->respondSuccess(
            trans('messages.user_unfollowed_success', ['name' => $model->name])
        );
    }

    /**
     * return success response after Delete a follower
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleDeleteFollowerSuccessResponse()
    {
        return $this->respondSuccess(trans('messages.follower_deleted_success'));
    }
}