<?php

namespace App\MsAuth\Config;

use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MigrationConfiguration
{
    private EntityManagerInterface $entityManager;

//    public function __construct(EntityManagerInterface $entityManager)
    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::createEntityManager();
    }

    public function getDependencyFactory(): DependencyFactory
    {
        // Menggunakan ExistingConfiguration untuk mengonfigurasi migrasi
        $config = new Configuration();

        // Set pengaturan migrasi
        $config->addMigrationsDirectory('Migrations', __DIR__ . '/../migrations');
        $config->setAllOrNothing(true);
        $config->setCheckDatabasePlatform(true);

        // DependencyFactory digunakan untuk mengelola dependensi migrasi
        return DependencyFactory::fromEntityManager(
            new ExistingConfiguration($config),
            new ExistingEntityManager($this->entityManager)
        );
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

}
