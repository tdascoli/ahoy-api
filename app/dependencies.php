<?php
declare(strict_types=1);

use App\Auth\ApiKeyAuth;
use App\Auth\AuthService;
use App\Auth\JwtAuth;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        JwtAuth::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $config = $settings['jwt'];

            $issuer = $config['issuer'];
            $lifetime = $config['lifetime'];
            $privateKey = $config['private_key'];
            $publicKey = $config['public_key'];

            return new JwtAuth($issuer, $lifetime, $privateKey, $publicKey);
        },
        ApiKeyAuth::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $config = $settings['apikey'];
            return new ApiKeyAuth($config['api_key']);
        },
        AuthService::class => function (ContainerInterface $container) {
            return new AuthService($container->get(\App\Domain\Profile\ProfileRepository::class));
        },
    ]);
};
