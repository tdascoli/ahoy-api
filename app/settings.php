<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'jwt' => [
                // The issuer name
                'issuer' => 'apollo29.com',
                // Max lifetime in seconds
                'lifetime' => 14400,
                // The private key
                'private_key' => getenv('AUTH_PRIVATE_KEY'),
                'public_key' => getenv('AUTH_PUBLIC_KEY'),
            ]
        ],
    ]);
};
