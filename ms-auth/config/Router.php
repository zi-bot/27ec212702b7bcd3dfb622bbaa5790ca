<?php

namespace App\MsAuth\Config;

use AltoRouter;
use App\Middleware\AuthMiddleware;
use App\Middleware\ErrorHandler;
use App\MsAuth\Controllers\UserController;
use http\Env\Request;
use Psr\Log\LoggerInterface;

class Router
{
    private AltoRouter $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    public function setupRoutes(UserController $userController): void
    {
        $this->router->map('POST', '/register', [$userController, 'register']);
        $this->router->map('POST', '/login', [$userController, 'login']);
        $this->router->map('GET', '/profile', function () use ($userController,) {
            $authMiddleware = new AuthMiddleware();
            $request = $_SERVER;
            $next = function ($request, $userData) use ($userController) {
                $userController->profile($userData);
            };
            $authMiddleware->handle($request, $next);
        });
    }

    public function matchAndDispatch()
    {
        $match = $this->router->match();

        if ($match && is_callable($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            ErrorHandler::handle404();
        }
    }
}
