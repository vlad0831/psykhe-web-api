<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class ApiController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @param mixed $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function responseOk($data, array $headers = [])
    {
        return response()->json($data, HttpResponse::HTTP_OK, $headers, JSON_PRETTY_PRINT);
    }

    /**
     * @param Exception $exception
     * @param array     $headers
     *
     * @return JsonResponse
     */
    public function reportError($exception, array $headers = [])
    {
        /** @var array $data */
        $data = [
            'error' => [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ],
        ];

        return response()->json($data, HttpResponse::HTTP_OK, $headers, JSON_PRETTY_PRINT);
    }

    /**
     * @param $data
     *
     * @return JsonResponse
     */
    public function responseCreated($data)
    {
        return response()->json($data, HttpResponse::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    public function responseNoContent()
    {
        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param $message
     * @param mixed|null $type
     *
     * @return JsonResponse
     */
    public function responseInternalError($message = null, $type = null)
    {
        $response = array_merge(
            $message ? ['message' => $message] : [],
            $type ? ['type' => $type] : [],
        );

        return response()->json($response, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function responseForbidden($message = null)
    {
        return response()->json(compact('message'), HttpResponse::HTTP_FORBIDDEN);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function responseUnauthorized($message = null)
    {
        return response()->json(compact('message'), HttpResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function responseNotFound($message = null)
    {
        return response()->json(compact('message'), HttpResponse::HTTP_NOT_FOUND);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function responseBadRequest($message = null)
    {
        return response()->json(compact('message'), HttpResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function responseValidationError($message = null)
    {
        return response()->json(compact('message'), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
