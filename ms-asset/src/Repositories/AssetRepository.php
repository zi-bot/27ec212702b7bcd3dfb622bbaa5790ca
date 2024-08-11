<?php

namespace App\MsAsset\Repositories;

use Doctrine\ORM\EntityRepository;
use App\MsAsset\Models\Asset;

class AssetRepository extends EntityRepository
{
    public function findAllAssets():array
    {
        return $this->findAll();
    }

    public function findAssetById(int $id): ?Asset
    {
        return $this->find($id);
    }

    public function save(Asset $asset): void
    {
        $this->_em->persist($asset);
        $this->_em->flush();
    }
}
