<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\JsonResponse;

class IsAllowedToSeePrivate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param null|string               $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $model = $request->route()->parameter('model');

        if (auth($guard)->id() === $model->user_id)
        {
            return $next($request);
        }

        $private = $model->user->properties()->where('key', 'private')->first();
        $isFollower = $model->user
            ->followers()
            ->where('user_id', auth($guard)->id())
            ->where('accepted', true)
            ->count();

        if ($private && $private->value && !$isFollower)
        {
            return new JsonResponse([
                'success' => false,
                'message' => trans('messages.privacy_access_denied'),
            ], 403);
        }

        return $next($request);
    }
}
