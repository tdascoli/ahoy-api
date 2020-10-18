<?php
declare(strict_types=1);

namespace App\Application\Actions\Queue;

use Psr\Http\Message\ResponseInterface as Response;

class ViewAction extends QueueAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $queue = $this->repository->find($id);

        $this->logger->info("Queue of id `${id}` was viewed.");

        return $this->respondWithData($queue);
    }
}
