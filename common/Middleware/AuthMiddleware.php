<?php

namespace App\Middleware;

use App\Logger\LoggerService;
use App\Redis\RedisService;
use Firebase\JWT\JWT;

class AuthMiddleware
{
    private LoggerService $logger;
    private RedisService $redisService;
    public function __construct()
    {
        $this->logger = LoggerService::getInstance();
        $this->redisService = new RedisService();
    }

    public function handle($request, $next)
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            ErrorHandler::handle401('Unauthorized: No token provided');
            return null;
        }

        $authHeader = $headers['Authorization'];
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        try {
            list($header, $payload, $signature) = explode('.', $jwt);
            $payload = base64_decode($payload);
            $arrayToken = json_decode($payload, true);
            $userName = $arrayToken['username'];
            $userId = $arrayToken['user_id'];
            $key = $userName.$userId;
            $result = $this->redisService->get($key);
            if (is_null($result)) {
                throw new \Exception('Token not found in key');
            }
            if ($result != $jwt) {
                throw new \Exception('Token not found in key');
            }
            return $next($request, $arrayToken);
        } catch (\Exception $e) {
            $this->logger->error("JWT Validation Failed", ['exception' => $e]);
            ErrorHandler::handle401('Unauthorized: Invalid token');
        }
    }
}
