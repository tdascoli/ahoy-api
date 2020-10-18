<?php
declare(strict_types=1);

namespace App\Application\Actions\Queue;

use Psr\Http\Message\ResponseInterface as Response;

class PostAction extends QueueAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $eventId = (int) $this->resolveArg('event_id');
        $queue = $this->repository->post($this->getFormData(),$eventId);
        $id = $queue->getId();
        $this->logger->info("Insert New Queue for Event ${id}."); // ?? undefined var??

        return $this->respondWithData($queue);
    }
}
