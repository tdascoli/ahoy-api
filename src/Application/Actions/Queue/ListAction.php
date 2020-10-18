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
        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
