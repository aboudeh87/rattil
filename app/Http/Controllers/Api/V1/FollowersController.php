<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use App\Transformers\V1\FollowerTransformer;

class FollowersController extends ApiController
{

    use ProfilesChecker, JsonResponses;

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
            User::withCount('recitations')
                ->whereIn(
                    'id',
                    $this->model
                        ->following()
                        ->where('followable_type', User::class)
                        ->get()
                        ->pluck('followable_id')
                        ->toArray()
                )
                ->paginate(),
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
            User::withCount('recitations')
                ->whereIn(
                    'id',
                    $this->model
                        ->followers()
                        ->get()
                        ->pluck('user_id')
                        ->toArray()
                )
                ->paginate(),
            new FollowerTransformer
        );
    }
}