<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class IsOwner
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param string                    $attribute // the name of model attribute
     * @param null|string               $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $attribute = 'model', $guard = null)
    {
        $model = $request->route()->parameter($attribute);

        if ($model->user_id !== auth($guard)->id())
        {
            throw new UnauthorizedException;
        }

        return $next($request);
    }
}
