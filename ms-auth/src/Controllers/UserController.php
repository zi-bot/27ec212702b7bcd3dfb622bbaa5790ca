<?php

namespace App\MsAuth\Controllers;

use App\Middleware\ErrorHandler;
use App\MsAuth\Models\User;
use App\MsAuth\Services\JwtService;
use App\MsAuth\Services\UserService;
use App\Redis\RedisService;
use App\Response\Response;
use const App\MsAuth\Services\USERNAME_ALREADY_EXISTS;
use JMS\Serializer\SerializerBuilder;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            ErrorHandler::handle400('Invalid input');
            return;
        }

        $result = $this->userService->registerUser($username, $password);
        if ($result == USERNAME_ALREADY_EXISTS) {
            ErrorHandler::handle400(USERNAME_ALREADY_EXISTS);
            return;
        }
        Response::success(null, code:201,msg: "User registered successfully");
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            ErrorHandler::handle400('Invalid input');
            return;
        }

        $result = $this->userService->authenticateUser($username, $password);
        if (!$result) {
            ErrorHandler::handle401('Authentication failed');
            return;
        }
        Response::success($result);
    }

    public function profile(array $userData): void
    {
        $username =$userData['username']??'';
        $user = $this->userService->getProfile($username);
        if (is_null($user)) {
            ErrorHandler::handle404();
            return;
        }
        Response::success($user);
    }
}
