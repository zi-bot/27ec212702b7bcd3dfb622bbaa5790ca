<?php

function applyMiddleware(array $middlewares, $handler)
{
    return array_reduce(array_reverse($middlewares), function ($next, $middleware) {
        return function () use ($next, $middleware) {
            call_user_func_array($middleware, [$_SERVER, $next]);
        };
    }, $handler);
}
