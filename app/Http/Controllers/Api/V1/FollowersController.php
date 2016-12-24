<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use App\Transformers\V1\UserTransformer;
use App\Transformers\V1\FollowerTransformer;

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

        if (
        !$user->following()
            ->where(['followable_type' => User::class, 'followable_id' => $model->id])
            ->count()
        )
        {
            $private = $model->properties()->whereKey('private')->first();

            $user->following()->create([
                'followable_type' => User::class,
                'followable_id'   => $model->id,
                'accepted'        => $private && $private->value ? false : true,
            ]);
        }

        return $this->handleFollowSuccessResponse($model);
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

        $ids = $user->followers()
            ->whereAccepted(false)
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('user_id')
            ->toArray();

        return $this->respondWithPagination(
            User::whereIn('id', $ids)->orderByRaw(\DB::raw("FIELD(id," . implode(',', $ids) . ")"))->paginate(),
            new UserTransformer
        );
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleFollowSuccessResponse(User $model)
    {
        return $this->respondSuccess(
            trans('messages.user_followed_success', ['name' => $model->name])
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