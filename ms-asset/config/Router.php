<?php

namespace App\MsAsset\Config;

use AltoRouter;
use App\Middleware\AuthMiddleware;
use App\Middleware\ErrorHandler;
use App\MsAsset\Controllers\AssetController;

class Router
{
    private AltoRouter $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    public function setupRoutes(AssetController $assetController): void
    {
        // Middleware yang digunakan
        $authMiddleware = new AuthMiddleware();
        $middlewares = [
            [$authMiddleware, 'handle'],
        ];

        // Rute dengan middleware
        $this->router->map('POST', '/assets', $this->applyMiddleware($middlewares, [$assetController, 'createAsset']));
        $this->router->map('GET', '/assets', $this->applyMiddleware($middlewares, [$assetController, 'listAssets']));

        $this->router->map('GET', '/assets/[i:id]', function ($id) use ($assetController, $authMiddleware) {
            $request = $_SERVER;
            $next = function ($request, $userData) use ($assetController, $id) {
                // Pass user data and asset ID to the controller method
                $assetController->detailAsset($id, $userData);
            };
            $authMiddleware->handle($request, $next);
        });
    }

    public function matchAndDispatch()
    {
        $match = $this->router->match();

        if ($match && is_callable($match['target'])) {
            $params = $match['params'];
            call_user_func_array($match['target'], $params);
        } else {
            ErrorHandler::handle404();
        }
    }
    private function applyMiddleware(array $middlewares, $handler)
    {
        return array_reduce(array_reverse($middlewares), function ($next, $middleware) {
            return function () use ($next, $middleware) {
                call_user_func_array($middleware, [$_SERVER, $next]);
            };
        }, $handler);
    }
}
