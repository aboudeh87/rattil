<?php

namespace App\Http\Controllers\Api\V1;


use App\Reason;
use App\Country;
use App\Language;
use App\Narration;
use App\Report;

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

    /**
     * Return languages list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function languages()
    {
        return $this->respond(
            Language::all()
                ->map(function (Language $model)
                {
                    return [
                        'key'  => $model->key,
                        'name' => $model->name,
                    ];
                })
                ->toArray()
        );
    }

    /**
     * Return narrations list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function narrations()
    {
        return $this->respond(
            Narration::with('names')
                ->get()
                ->map(function (Narration $model)
                {
                    $name = $model->names->where('language_key', \App::getLocale())->first();

                    return [
                        'id'     => $model->id,
                        'weight' => $model->weight,
                        'name'   => $name ? $name->name : $model->names->first()->name,
                    ];
                })
                ->toArray()
        );
    }

    /**
     * Return reasons list
     *
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reasons($type)
    {
        return $this->respond(
            Reason::with('content')
                ->whereModel(Report::AVAILABLE_TYPES[$type])
                ->get()
                ->map(function (Reason $model)
                {
                    $name = $model->content->where('language_key', \App::getLocale())->first();

                    return [
                        'id'   => $model->id,
                        'name' => $name ? $name->text : $model->content->first()->text,
                    ];
                })
                ->toArray()
        );
    }
}