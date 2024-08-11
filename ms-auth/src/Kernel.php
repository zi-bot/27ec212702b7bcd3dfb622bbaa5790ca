<?php

namespace App\MsAuth;

use App\MsAuth\Config\EntityManagerFactory;
use App\MsAuth\Config\Router;
use App\Logger\LoggerService;
use App\MsAuth\Repositories\UserRepository;
use App\MsAuth\Services\UserService;
use App\MsAuth\Controllers\UserController;
use App\Redis\RedisService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class Kernel
{
    protected EntityManager $entityManager;
    protected Router $router;

    protected RedisService $redis;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::createEntityManager();
        $this->router = new Router();
        $logger = LoggerService::getInstance('ms-auth');
        $this->redis = new RedisService($logger);
    }

    public function boot(): void
    {
        $classMetaData = new ClassMetaData('App\MsAuth\Models\User');
        $userRepository = new UserRepository($this->entityManager, $classMetaData);
        $userService = new UserService($userRepository, $this->redis);
        $userController = new UserController($userService);

        $this->router->setupRoutes($userController);
    }

    public function handleRequest(): void
    {
        $this->boot();
        $this->router->matchAndDispatch();
    }
}
