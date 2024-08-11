<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\MsAsset\Kernel;
use App\Middleware\ErrorHandler;

// Set global error handler to convert errors into exceptions
set_error_handler(
/**
 * @throws ErrorException
 */
    callback: function ($severity, $message, $file, $line) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
);

// Set global exception handler
set_exception_handler(function ($e) {
    ErrorHandler::handle($e);
});

// Set shutdown function to catch fatal errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        $e = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
        ErrorHandler::handle($e);
    }
});

$kernel = new Kernel();
$kernel->handleRequest();
