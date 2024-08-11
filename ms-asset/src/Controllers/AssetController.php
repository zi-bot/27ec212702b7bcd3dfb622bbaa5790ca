<?php

namespace App\MsAsset\Controllers;

use App\Logger\LoggerService;
use App\Middleware\ErrorHandler;
use App\MsAsset\Models\Asset;
use App\MsAsset\Services\AssetService;
use App\Middleware\AuthMiddleware;
use App\Response\Response;

class AssetController
{
    private AssetService $assetService;
    private LoggerService $logger;
    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
        $this->logger = LoggerService::getInstance('ms-asset');
    }

    public function createAsset(array $request, array $userData): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $value = $data['value'] ?? null;
            $name = $data['name'] ?? null;
            if (!$value || !$name) {
                ErrorHandler::handle400('Invalid input');
                return;
            }
            $assetData = array(
                'value' => $value,
                'name' => $name,
                'owner_id' => $userData['user_id'],
                'owner_name' => $userData['username'],
            );
            $this->assetService->createAsset($assetData);
            Response::success(null, 201, "Asset created");
        } catch (\Exception $e) {
            $this->logger->error("Failed to create asset: ".$e->getMessage());
            ErrorHandler::handle($e);
        }
    }

    public function listAssets(): void
    {
        $assets = $this->assetService->listAssets();
        Response::success($assets);
    }

    public function getAssetById(int $id): void
    {
        $asset = $this->assetService->getAssetById($id);
        if (is_null($asset)) {
            ErrorHandler::handle404("asset not found");
            return;
        }
        Response::success($asset);
    }
}
