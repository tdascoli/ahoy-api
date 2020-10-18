<?php
declare(strict_types=1);

namespace App\Application\Actions\Queue;

use App\Application\Actions\Action;
use App\Domain\Queue\QueueRepository;
use Psr\Log\LoggerInterface;

abstract class QueueAction extends Action
{
    /**
     * @var QueueRepository
     */
    protected $repository;

    /**
     * @param LoggerInterface $logger
     * @param QueueRepository  $repository
     */
    public function __construct(LoggerInterface $logger, QueueRepository $repository)
    {
        parent::__construct($logger);
        $this->repository = $repository;
    }
}
