<?php

namespace App\Http\Controllers\Api\V1;


use App\Events\NewRecitationPosted;
use App\User;
use App\Recitation;
use App\Http\Requests\RecitationRequest;
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
     * Create a new model instance
     *
     * @param \App\Http\Requests\RecitationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RecitationRequest $request)
    {

        $model = new Recitation($request->all());
        $model->user_id = $this->user->id;
        $model->save();

        $model->mentions()->sync($request->get('mentions', []));

        $file = $request->file('file');
        $file->storeAs('temp', $model->id . '.' . $file->extension(), 'local');

        return $this->respondSuccess(trans('messages.posted_success'));
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