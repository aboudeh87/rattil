<?php

namespace App\Http\Controllers\Api\V1;


use App\User;
use App\Recitation;
use Illuminate\Http\Request;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use App\Events\RecitationUpdated;
use App\Events\NewRecitationPosted;
use App\Http\Requests\StoreRecitationRequest;
use App\Http\Requests\UpdateRecitationRequest;
use App\Transformers\V1\RecitationTransformer;

class RecitationController extends ApiController
{

    use ProfilesChecker, JsonResponses;

    const PATH = 'public/recitations/';

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
     * @param null|string $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function recitations($model = null)
    {
        if (!$this->isAllowed($model))
        {
            return $this->accessDeniedResponse();
        }

        return $this->respondWithPagination(
            Recitation::withCount('comments', 'favorators', 'likes')
                ->whereUserId($this->model->id)
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
                ->whereIn('user_id', $this->user->following()->whereAccepted(true)->pluck('id')->toArray())
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
                ->whereNotin('user_id', $this->getPrivateUserIds())
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
                ->whereNotin('user_id', $this->getPrivateUserIds())
                ->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(),
            new RecitationTransformer
        );
    }

    /**
     * Create a new model instance
     *
     * @param \App\Http\Requests\StoreRecitationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRecitationRequest $request)
    {
        $model = new Recitation($request->all());
        $model->user_id = $this->user->id;
        $model->save();

        $file = $request->file('file');
        $filename = $this->getFileName($model);
        $file->storeAs(self::PATH, $filename);

        $model->url = self::PATH . $filename;
        $model->save();

        $model->mentions()->sync($request->get('mentions', []));

        \Event::fire(new NewRecitationPosted($model, $file));

        return $this->respondSuccess(trans('messages.posted_success'));
    }

    /**
     * Update a model instance
     *
     * @param \App\Http\Requests\UpdateRecitationRequest $request
     * @param \App\Recitation                            $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRecitationRequest $request, Recitation $model)
    {
        $model->fill($request->only([
            'description',
            'sura_id',
            'narration_id',
            'from_verse',
            'to_verse',
        ]));

        $model->save();

        $model->mentions()->sync($request->get('mentions', []));

        \Event::fire(new RecitationUpdated($model));

        return $this->respondSuccess(trans('messages.updated_success'));
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

    /**
     * Add a listener to recitation
     *
     * @param \App\Recitation $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listen(Recitation $model)
    {
        $user_id = $this->user ? $this->user->id : null;

        $listener = $model->listeners()
            ->where('user_id', $user_id)
            ->firstOrNew(['user_id' => $user_id]);

        $listener->count++;
        $listener->save();

        return $this->respondSuccess(trans('messages.updated_success'));
    }

    /**
     * delete a specific model
     *
     * @param \App\Recitation $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Recitation $model)
    {
        $model->delete();

        \Storage::delete(self::PATH . $this->getFileName($model));

        return $this->respondSuccess(trans('messages.recitation_deleted'));
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
            ->whereDisabled(false)
            ->whereNotin('user_id', $this->getPrivateUserIds());

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

    /**
     * Get private users IDs array
     *
     * @return array
     */
    protected function getPrivateUserIds()
    {
        return User::whereHas(
            'properties',
            function ($query)
            {
                $query->whereKey('private')->whereValue(true);
            })
            ->where('id', '!=', $this->user->id)
            ->get(['id'])
            ->pluck('id')
            ->toArray();
    }

    /**
     * Get the file name of the model
     *
     * @param \App\Recitation $model
     *
     * @return string
     */
    protected function getFileName(Recitation $model)
    {
        return md5('recitation-' . $model->id);
    }
}