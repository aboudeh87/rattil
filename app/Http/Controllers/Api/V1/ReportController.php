<?php

namespace App\Http\Controllers\Api\V1;


use App\Report;
use Illuminate\Http\Request;

class ReportController extends ApiController
{

    /**
     * Logged in user
     *
     * @var \App\User
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Recitation          $type
     * @param integer                  $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $type, $model)
    {
        $class = Report::AVAILABLE_TYPES[$type];

        $model = $class::findOrFail($model);

        if ($model->verified)
        {
            return $this->respondError(trans('messages.' . $type . '_is_verified'), 423);
        }

        if ($this->checkIfIsReportedBefore($model))
        {
            return $this->respondSuccess(trans('messages.already_reported'));
        }

        $this->validate($request, [
            'reason_id' => 'required|exists:reasons,id',
            'message'   => 'max:500',
        ]);

        Report::create([
            'reportable_type' => $class,
            'reportable_id'   => $model->id,
            'user_id'         => $this->user->id,
            'reason_id'       => $request->get('reason_id'),
            'message'         => $request->get('message', null),
        ]);

        return $this->respondSuccess(trans('messages.reported_success'));
    }

    /**
     * Check if the user report this model before
     *
     * @param $model
     *
     * @return bool
     */
    protected function checkIfIsReportedBefore($model)
    {
        return (bool) Report::where([
            'reportable_type' => get_class($model),
            'reportable_id'   => $model->id,
            'user_id'         => $this->user->id,
            'closed'          => false,
        ])->count();
    }
}