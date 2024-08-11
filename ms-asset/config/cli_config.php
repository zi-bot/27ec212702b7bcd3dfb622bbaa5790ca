#!/usr/bin/env php
<?php

use App\MsAsset\Config\MigrationConfiguration;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Application;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;

//require_once __DIR__ . '/doctrine.php';
require_once __DIR__.'/../vendor/autoload.php';

// Membuat instance MigrationConfiguration
$migrationConfig = new MigrationConfiguration();

// Mendapatkan DependencyFactory dari konfigurasi migrasi
$dependencyFactory = $migrationConfig->getDependencyFactory();

// Membuat instance EntityManager
$entityManager = $migrationConfig->getEntityManager();

// Konfigurasi Symfony Console Application
$helperSet = new HelperSet([
    'em' => new EntityManagerHelper($entityManager),
]);

$cli = new Application('Doctrine Command Line Interface');
$cli->setHelperSet($helperSet);

// Mendaftarkan command migrasi
$cli->addCommands([
    new DiffCommand($dependencyFactory),
    new ExecuteCommand($dependencyFactory),
    new GenerateCommand($dependencyFactory),
    new LatestCommand($dependencyFactory),
    new MigrateCommand($dependencyFactory),
    new RollupCommand($dependencyFactory),
    new StatusCommand($dependencyFactory),
    new UpToDateCommand($dependencyFactory),
    new VersionCommand($dependencyFactory),
]);

$cli->run();
