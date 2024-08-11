<?php

namespace App\Redis;

use App\Logger\LoggerService;
use Predis\Client;
use Psr\Log\LoggerInterface;

class RedisService
{
    private Client $redis;

    private LoggerInterface $logger;

    public function __construct()
    {
        $this->iniClient();
        $this->logger = LoggerService::getInstance('redis');
    }

    private function iniClient(): Void
    {
        $this->redis = new Client([
            'scheme' => 'redis',
            'host'   => '127.0.0.1',
            'port'   => 6379,
            'database' => 1,
        ]);
    }

    public function set(string $key, $value, int $expire = 0): void
    {
        try {
            if ($expire > 0) {
                $this->redis->setex($key, $expire, $value);
            } else {
                $this->redis->set($key, $value);
            }
        } catch (\Exception $e) {
            $this->logger->error("Failed to set key: {$e->getMessage()}");
        }
    }

    public function get(string $key): ?string
    {
        try {
            $result = $this->redis->get($key);
            return $result ?? null;
        } catch (\Exception $e) {
            $this->logger->error("Failed to get key: {$e->getMessage()}");
            return null;
        }
    }

    public function publish(string $channel, string $message): void
    {
        try {
            $this->redis->publish($channel, $message);
        } catch (\Exception $e) {
            $this->logger->error("Failed to publish message: {$e->getMessage()}");
        }
    }

    public function subscribe(string $channel, callable $callback): void
    {
        try {
            $pid = pcntl_fork();

            if ($pid == -1) {
                // Error during fork
                $this->logger->error("Failed to fork the process");
                return;
            } elseif ($pid) {
                // Parent process: continue with the main application
                return;
            } else {
                // Child process: handle the subscription
                $pubsub = $this->redis->pubSubLoop();
                $pubsub->subscribe($channel);

                foreach ($pubsub as $message) {
                    if ($message->kind === 'message') {
                        try {
                            $callback($message->payload);
                        } catch (\Exception $e) {
                            $this->logger->error("Error handling message: {$e->getMessage()}");
                        }
                    }
                }

                $pubsub->unsubscribe();
                exit(0); // End the child process after work is done
            }
        } catch (\Exception $e) {
            $this->logger->error("Failed to subscribe to channel: {$e->getMessage()}");
        }
    }
}
