<?php

namespace App\MsAsset;

use App\MsAsset\Config\EntityManagerFactory;
use App\MsAsset\Config\Router;
use App\Logger\LoggerService;
use App\MsAsset\Repositories\AssetRepository;
use App\MsAsset\Services\AssetService;
use App\MsAsset\Controllers\AssetController;
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
        $logger = LoggerService::getInstance('ms-asset');
        $this->redis = new RedisService($logger);
    }

    public function boot(): void
    {
        $classMetaData = new ClassMetaData('App\MsAsset\Models\Asset');
        $assetRepository = new AssetRepository($this->entityManager, $classMetaData);
        $assetService = new AssetService($assetRepository, $this->redis);
        $assetController = new AssetController($assetService);

        $this->router->setupRoutes($assetController);
    }

    public function handleRequest(): void
    {
        $this->boot();
        $this->router->matchAndDispatch();
    }
}
