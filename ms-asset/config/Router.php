<?php

namespace App\MsAsset\Config;

use AltoRouter;
use App\Middleware\AuthMiddleware;
use App\Middleware\ErrorHandler;
use App\MsAsset\Controllers\AssetController;
use http\Env\Request;
use Psr\Log\LoggerInterface;

class Router
{
    private AltoRouter $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    public function setupRoutes(AssetController $assetController): void
    {
        $authMiddleware = new AuthMiddleware();

        $middlewares = [
            [$authMiddleware, 'handle'],
        ];

        $this->router->map('POST', '/assets', applyMiddleware($middlewares, [$assetController, 'createAsset']));
        $this->router->map('GET', '/assets', applyMiddleware($middlewares, [$assetController, 'listAssets']));


//        $this->router->map('POST', '/register', [$userController, 'register']);
//        $this->router->map('POST', '/login', [$userController, 'login']);
//        $this->router->map('GET', '/profile', function () use ($userController,) {
//            $authMiddleware = new AuthMiddleware();
//            $request = $_SERVER;
//            $next = function ($request, $userData) use ($userController) {
//                $userController->profile($userData);
//            };
//            $authMiddleware->handle($request, $next);
//        });
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


function applyMiddleware(array $middlewares, $handler)
{
    return array_reduce(array_reverse($middlewares), function ($next, $middleware) {
        return function () use ($next, $middleware) {
            call_user_func_array($middleware, [$_SERVER, $next]);
        };
    }, $handler);
}
