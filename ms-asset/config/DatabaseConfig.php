<?php

namespace App\MsAsset\Config;

class DatabaseConfig
{
    public static function getConnectionParams(): array
    {
        return [
            'driver'   => 'pdo_pgsql',
            'user'     => 'postgres',
            'password' => 'toor',
            'dbname'   => 'asset_service',
            'host'     => 'localhost',
            'port'     => 5432,
        ];
    }

    public static function getPaths(): array
    {
        return [__DIR__ . '/../src/Models'];
    }

    public static function isDevMode(): bool
    {
        return true;
    }
}
