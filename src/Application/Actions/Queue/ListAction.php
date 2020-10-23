<?php
declare(strict_types=1);

namespace App\Application\Actions\Queue;

use Psr\Http\Message\ResponseInterface as Response;

class ListAction extends QueueAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $event_id = (int) $this->resolveArg('event_id');
        $queue = $this->repository->list($event_id);

        $this->logger->info("Queue list was viewed.");

        return $this->respondWithData($queue);
    }
}
