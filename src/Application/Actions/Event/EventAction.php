<?php
declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Application\Actions\Action;
use App\Domain\Event\EventRepository;
use Psr\Log\LoggerInterface;

abstract class EventAction extends Action
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * @param LoggerInterface $logger
     * @param EventRepository  $repository
     */
    public function __construct(LoggerInterface $logger, EventRepository $repository)
    {
        parent::__construct($logger);
        $this->repository = $repository;
    }
}
