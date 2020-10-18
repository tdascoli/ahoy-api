<?php
declare(strict_types=1);

namespace App\Application\Actions\Event;

use Psr\Http\Message\ResponseInterface as Response;

class ListAction extends EventAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->repository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
