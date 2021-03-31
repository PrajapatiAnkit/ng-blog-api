<?php


namespace App\Helper;


class ResponseHelper
{
    /**
     * This function returns the success response for APIs
     * @param $message
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successResponse($message, $data = [], $code = 200)
    {
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $data,
            'message' => $message,
        ],$code);
    }

    /**
     * This function returns the error response for APIs
     * @param $message
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorResponse($message, $code = 500, $data = [])
    {
        return response()->json([
            'success' => false,
            'error' => true,
            'data' => $data,
            'message' => $message,
        ],$code);
    }
}
