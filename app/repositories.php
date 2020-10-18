<?php
declare(strict_types=1);

use App\Domain\Event\EventRepository;
use App\Domain\Profile\ProfileRepository;
use App\Domain\Queue\QueueRepository;
use App\Infrastructure\Persistence\Event\DefaultEventRepository;
use App\Infrastructure\Persistence\Profile\PersistenceProfileRepository;
use App\Infrastructure\Persistence\Queue\DefaultQueueRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our EventRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        \App\Domain\Event\EventRepository::class => \DI\autowire(DefaultEventRepository::class),
    ]);

    $containerBuilder->addDefinitions([
        ProfileRepository::class => \DI\autowire(PersistenceProfileRepository::class)
    ]);

    $containerBuilder->addDefinitions([
        EventRepository::class => \DI\autowire(DefaultEventRepository::class)
    ]);

    $containerBuilder->addDefinitions([
        QueueRepository::class => \DI\autowire(DefaultQueueRepository::class)
    ]);
};
