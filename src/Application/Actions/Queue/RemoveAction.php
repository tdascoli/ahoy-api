<?php
declare(strict_types=1);

namespace App\Application\Actions\Queue;

use Psr\Http\Message\ResponseInterface as Response;

class RemoveAction extends QueueAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        $uid = (int) $this->resolveArg('uid');
        $this->logger->info("try to remove Queue ${uid}.");

        $success = $this->repository->remove($uid);
        $this->logger->info("Queue removed ${success}.");
        $this->logger->info("Queue removed ${uid}.");

        return $this->respondWithData($success);
    }
}