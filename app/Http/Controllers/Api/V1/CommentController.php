<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Comment;
use App\Recitation;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use App\Http\Requests\CommentRequest;

class CommentController extends ApiController
{

    use ProfilesChecker, JsonResponses;

    /**
     * Logged in user
     *
     * @var User
     */
    protected $user;

    /**
     * CommentController constructor.
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
     * Create a new model instance
     *
     * @param \App\Http\Requests\CommentRequest $request
     * @param \App\Recitation                   $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CommentRequest $request, Recitation $model)
    {
        $comment = new Comment;
        $comment->text = $request->get('text', null);
        $comment->user_id = $this->user->id;

        if ($this->user->certified)
        {
            // TODO save the file and save the URL to comment model
        }

        $model->comments()->save($comment);

        return $this->respondSuccess(trans('messages.comment_posted'));
    }

    /**
     * delete a model instance
     *
     * @param \App\Comment $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $model)
    {
        $model->delete();

        // TODO delete the file after deleting the model
        return $this->respondSuccess(trans('messages.comment_deleted'));
    }
}