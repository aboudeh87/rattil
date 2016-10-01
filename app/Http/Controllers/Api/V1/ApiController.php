<?php

namespace App\Http\Controllers\Api\V1;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Class ApiController
 *
 * @package GeniusTS\Core
 */
class ApiController extends Controller
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var integer
     */
    protected $limit;

    /**
     * @var string
     */
    protected $keyword;

    /**
     * ApiController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $keyword = trim($this->request->get('keyword', null));

        $this->keyword = $keyword ? '%' . str_replace(' ', '%', $keyword) . '%' : null;
        $this->limit = (int) trim($this->request->get('limit', 15));
    }

    /**
     * return a JSON response
     *
     * @param array $data
     * @param int   $code
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond(array $data, $code = 200, array $headers = [])
    {
        return response()->json($data, $code, $headers);
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
    public function respondError($errors, $code = 400, array $headers = [])
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
    public function respondSuccess($messages, $code = 200, array $headers = [])
    {
        return $this->respond(['success' => true, 'message' => $messages], $code, $headers);
    }
}