<?php

namespace App\Http\Controllers\Api\V1;


use App\Transformers\Transformer;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Class ApiController
 *
 * @package App\Http\Controllers\Api\V1
 */
class ApiController extends Controller
{

    /**
     * return a JSON response
     *
     * @param array $data
     * @param int   $code
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond(array $data, $code = 200, array $headers = [])
    {
        return response()->json($data, $code, $headers);
    }

    /**
     * return a response with pagination
     *
     * @param \Illuminate\Contracts\Pagination\Paginator $paginate
     * @param \App\Transformers\Transformer              $transformer
     * @param int                                        $code
     * @param array                                      $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithPagination(Paginator $paginate, Transformer $transformer, $code = 200, $headers = [])
    {
        return $this->respond([
            'total'       => $paginate->total(),
            'limit'       => $paginate->perPage(),
            'currentPage' => $paginate->currentPage(),
            'data'        => $transformer->transform($paginate->getCollection())->toArray(),
        ], $code, $headers);
    }

    /**
     * return error response
     *
     * @param       $errors
     * @param int   $code
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError($errors, $code = 400, array $headers = [])
    {
        return $this->respond(['success' => false, 'message' => $errors], $code, $headers);
    }

    /**
     * return success response
     *
     * @param       $messages
     * @param int   $code
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondSuccess($messages, $code = 200, array $headers = [])
    {
        return $this->respond(['success' => true, 'message' => $messages], $code, $headers);
    }
}