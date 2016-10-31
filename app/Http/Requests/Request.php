<?php

namespace App\Http\Requests;


use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson())
        {
            return new JsonResponse([
                'success' => false,
                'message' => trans('messages.data_validation_error'),
                'errors'  => $errors,
            ], 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }

}