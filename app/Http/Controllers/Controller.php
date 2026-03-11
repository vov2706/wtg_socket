<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function responseSuccess(?string $message = null, array $options = [])
    {
        return response([
            ...$options,
            'status' => 'success',
            'message' => $message,
        ]);
    }

    public function responseError(string $message = 'fail', array $options = [])
    {
        return response([
            ...$options,
            'status' => 'error',
            'message' => $message,
        ]);
    }
}
