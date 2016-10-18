<?php

namespace App\Http\Controllers\Api\V1;


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
            $this->model->following()->withCount('recitations')->paginate(),
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
            $this->model->followers()->withCount('recitations')->paginate(),
            new FollowerTransformer
        );
    }
}