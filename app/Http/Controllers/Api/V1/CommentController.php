<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Comment;
use App\Recitation;
use Illuminate\Http\Request;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use App\Events\RecitationUpdated;
use App\Events\NewRecitationPosted;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\StoreRecitationRequest;
use App\Http\Requests\UpdateRecitationRequest;
use App\Transformers\V1\RecitationTransformer;

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

        if (!$this->user->certified)
        {
            // TODO save the file and save the URL to comment model
        }

        $model->comments()->save($comment);

        return $this->respondSuccess(trans('messages.comment_posted'));
    }
}