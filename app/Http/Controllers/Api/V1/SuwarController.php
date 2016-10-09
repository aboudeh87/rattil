<?php

namespace App\Http\Controllers\Api\V1;


use App\Sura;
use App\Transformers\V1\SuraTransformer;

class SuwarController extends ApiController
{

    /**
     * Return items of model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->respondWithPagination(Sura::paginate(), new SuraTransformer);
    }
}