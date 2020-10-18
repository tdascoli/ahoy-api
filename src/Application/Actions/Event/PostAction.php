<?php
declare(strict_types=1);

namespace App\Application\Actions\Event;

use Psr\Http\Message\ResponseInterface as Response;

class PostAction extends EventAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $event = $this->repository->post($this->getFormData(),null);
        $id = $event->getId();
        $this->logger->info("Insert New Event ${id}."); // ?? undefined var??

        return $this->respondWithData($event);
    }
}
