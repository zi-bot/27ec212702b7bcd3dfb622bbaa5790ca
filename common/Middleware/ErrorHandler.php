<?php

namespace App\Middleware;

use App\Logger\LoggerService;

class ErrorHandler
{
    public static function handle(\Throwable $e):void
    {
        $logger = LoggerService::getInstance();
        $logger->error($e);
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage()
        ]);
    }

    public static function handle404($msg = 'Route not found'):void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $msg,
            'error' =>'Not Found.'
        ]);
    }

    public static function handle400($msg, $err = 'Bad Request'):void
    {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $msg,
            'error' => $err
        ]);
    }

    public static function handle401($msg = "Unauthorized access."):void
    {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $msg,
            'error' => 'Unauthorized.'
        ]);
    }
}
