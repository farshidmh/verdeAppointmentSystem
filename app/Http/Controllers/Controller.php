<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message = null): JsonResponse
    {
        $response ['success'] = true;

        if (!is_null($result)) {
            $response['data'] = $result;
        }

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error,$errorMessages = [], int $code = 404): JsonResponse
    {
        $response ['success'] = false;

        if (!is_null($error)) {
            $response['data'] = $error;
        }

        if ($errorMessages) {
            $response['message'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
