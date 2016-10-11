<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Recitation;
use App\Transformers\V1\RecitationTransformer;

class RecitationController extends ApiController
{

    /**
     * Logged in user
     *
     * @var User
     */
    protected $user;

    /**
     * RecitationController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next)
        {
            $this->user = \Auth::guard('api')->user();

            return $next($request);
        });
    }

    /**
     * Return The recitation of logged in user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRecitation()
    {
        return $this->respondWithPagination(
            Recitation::withCount('comments', 'favorators', 'likes')
                ->whereUserId($this->user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(),
            new RecitationTransformer
        );
    }

    /**
     * Return a specific model
     *
     * @param \App\Recitation $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Recitation $model)
    {
        return $this->respond(
            (new RecitationTransformer)->setShow(true)->transform(
                $model->withCount('comments', 'favorators', 'likes')->first()
            )
        );
    }
}