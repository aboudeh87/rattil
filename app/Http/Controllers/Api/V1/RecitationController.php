<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Recitation;
use Illuminate\Http\Request;
use App\Events\NewRecitationPosted;
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
                ->whereDisabled(false)
                ->orderBy('created_at', 'desc')
                ->paginate(),
            new RecitationTransformer
        );
    }

    /**
     * Return The recitation of followed users
     * for the current user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function following()
    {
        return $this->respondWithPagination(
            Recitation::withCount('comments', 'favorators', 'likes')
                ->whereIn(
                    'user_id',
                    $this->user->following->pluck('id')->toArray()
                )
                ->whereDisabled(false)
                ->orderBy('created_at', 'desc')
                ->paginate(),
            new RecitationTransformer
        );
    }

    /**
     * Return the latest recitations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        return $this->respondWithPagination(
            Recitation::withCount('comments', 'favorators', 'likes')
                ->whereDisabled(false)
                ->orderBy('created_at', 'desc')
                ->paginate(),
            new RecitationTransformer
        );
    }

    /**
     * Return list of popular recitations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function popular()
    {
        return $this->respondWithPagination(
            Recitation::withCount('comments', 'favorators', 'likes')
                ->whereDisabled(false)
                ->orderBy('likes_count', 'desc')
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

        \Event::fire(new NewRecitationPosted($model, $file));

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
        if ($model->disabled)
        {
            return $this->respondError(trans('messages.recitation_removed'));
        }

        return $this->respond(
            (new RecitationTransformer)->setShow(true)->transform(
                $model->withCount('comments', 'favorators', 'likes')->first()
            )
        );
    }

    /**
     * Search in recitation
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $this->validate($request, ['keyword' => 'required|min:2']);

        $suwar = $request->get('sura_id', []);
        $narrations = $request->get('narration_id', []);
        $keyword = $request->get('keyword', null);

        if ($suwar && !is_array($suwar))
        {
            $suwar = explode(',', $suwar);
        }

        if ($narrations && !is_array($narrations))
        {
            $narrations = explode(',', $narrations);
        }

        $models = Recitation::withCount('comments', 'favorators', 'likes')
            ->whereDisabled(false);

        if ($keyword)
        {
            $keyword = '%' . str_replace(' ', '%', $keyword) . '%';
            $models->whereHas('sura.content', function ($query) use ($keyword)
            {
                $query->where('name', 'like', $keyword)
                    ->orWhere('definition', 'like', $keyword);
            });
        }

        if (count($suwar))
        {
            $models->whereIn('sura_id', $suwar);
        }

        if (count($narrations))
        {
            $models->whereIn('narration_id', $narrations);
        }

        return $this->respondWithPagination(
            $models->orderBy('created_at', 'desc')
                ->paginate(),
            new RecitationTransformer
        );
    }
}