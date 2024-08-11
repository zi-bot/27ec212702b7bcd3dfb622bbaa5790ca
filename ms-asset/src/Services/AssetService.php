<?php

namespace App\MsAsset\Services;

use App\Logger\LoggerService;
use App\MsAsset\Models\Asset;
use App\MsAsset\Repositories\AssetRepository;
use App\Redis\RedisService;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializerBuilder;

class AssetService
{
    private AssetRepository $assetRepository;
    private RedisService $redisService;
    private LoggerService $logger;

    public function __construct(AssetRepository $assetRepository, RedisService $redisService)
    {
        $this->assetRepository = $assetRepository;
        $this->redisService = $redisService;
        $this->logger = LoggerService::getInstance('ms-auth');
    }

    public function listAssets(): array
    {
        return $this->assetRepository->findAllAssets();
    }

    public function getAssetById(int $id): ?Asset
    {
        return $this->assetRepository->findAssetById($id);
    }

    public function createAsset(array $data): ?Asset
    {
        try {
            $asset = new Asset();
            $asset->setValue($data['value']);
            $asset->setName($data['name']);
            $asset->setOwnerId($data['owner_id']);
            $asset->setOwnerName($data['owner_name']);
            $this->assetRepository->save($asset);
            $serializer = SerializerBuilder::create()->build();

            $this->redisService->publish("ms-asset", $serializer->serialize($asset, 'json'));
        } catch (\Exception $e) {
            $this->logger->error("Failed to create asset: " . $e->getMessage());
            throw new \Exception("Failed to create asset: " . $e->getMessage());
        }
        return $asset;
    }
}
