<?php

namespace App\Http\Controllers\Api\V1;


use App\Country;

class AssistsController extends ApiController
{

    /**
     * Return countries list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries()
    {
        return $this->respond(
            Country::with('names')
                ->get()
                ->map(function (Country $model)
                {
                    $name = $model->names->where('language_key', \App::getLocale())->first();

                    return [
                        'key'  => $model->key,
                        'name' => $name ? $name->name : $model->names->first()->name,
                    ];
                })
                ->toArray()
        );
    }
}