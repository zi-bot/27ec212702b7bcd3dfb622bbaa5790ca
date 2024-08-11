<?php

namespace App\Logger;

use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class LoggerService implements LoggerInterface
{
    private static ?LoggerService $instance = null;
    private Logger $logger;

    // Private constructor to prevent direct object creation
    private function __construct(string $channel = 'app')
    {
        $this->logger = new Logger($channel);

        // Use a rotating file handler to maintain a log file per day
        $logFilePath = __DIR__ . '/../../logs/app.log';
        $this->logger->pushHandler(new RotatingFileHandler($logFilePath, 7, Logger::DEBUG));
//        $this->logger->pushHandler(new StreamHandler('php://output', Logger::DEBUG));
    }

    // Public static method to get the singleton instance
    public static function getInstance(string $channel = 'app'): LoggerService
    {
        if (self::$instance === null) {
            self::$instance = new LoggerService($channel);
        }

        return self::$instance;
    }

    // Implement the LoggerInterface methods
    public function emergency($message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
