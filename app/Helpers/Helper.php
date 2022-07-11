<?php

namespace App\Http\Helpers;

class Helper
{
    public static function errorJson()
    {
        return response()->json(['message' => 'An error occurred while processing the request. Please consult backend log.'], 500);
    }

    public static function jsonNotFound()
    {
        return response()->json(['message' => 'Data not found'], 404);
    }

    public static function jsonData($data, $statusCode = 200)
    {
        return response()->json($data, $statusCode);
    }

    public static function jsonValidation($validation)
    {
        return response()->json($validation->errors(), 422);
    }
}
