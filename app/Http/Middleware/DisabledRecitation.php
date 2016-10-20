<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\JsonResponse;

class DisabledRecitation
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $model = $request->route()->parameter('model');

        if ($model->disabled)
        {
            return new JsonResponse([
                'success' => false,
                'message' => trans('messages.recitation_removed'),
            ]);
        }

        return $next($request);
    }
}
