<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * The api response
     *
     * @param array $body
     * @param int $code
     * @return JsonResponse
     */
    protected function response($body = [], $code = 200)
    {
        $body['status'] = $code == 200 ? 'Success' : 'Failed';

        return response()->json($body, $code);
    }
}
