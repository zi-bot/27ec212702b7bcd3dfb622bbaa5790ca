<?php

namespace App\MsAuth\Config;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

class EntityManagerFactory
{
    public static function createEntityManager(): EntityManager
    {
        $paths = DatabaseConfig::getPaths();
        $isDevMode = DatabaseConfig::isDevMode();
        $connectionParams = DatabaseConfig::getConnectionParams();

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
        $connection = DriverManager::getConnection($connectionParams, $config);
        $entitymanager = new EntityManager($connection, $config);
        return $entitymanager;
    }
}
