<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Comment;
use App\Recitation;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\CommentRequest;

class CommentController extends ApiController
{

    use ProfilesChecker, JsonResponses;

    const PATH = 'public/comments/';

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
        $model->comments()->save($comment);

        if ($this->user->certified)
        {
            $file = $request->file('file');

            if ($file instanceof UploadedFile)
            {
                $filename = $this->getFileName($comment);

                $file->storeAs(self::PATH, $filename);
                $comment->url = \Request::root() . '/' . self::PATH . $filename;
                $comment->save();
            }
        }

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

        \Storage::delete(self::PATH . $this->getFileName($model));

        // TODO delete the file after deleting the model
        return $this->respondSuccess(trans('messages.comment_deleted'));
    }

    /**
     * Get the file name of the model
     *
     * @param \App\Comment $model
     *
     * @return string
     */
    protected function getFileName(Comment $model)
    {
        return md5('comment-' . $model->id);
    }
}