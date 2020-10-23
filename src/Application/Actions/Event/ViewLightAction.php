<?php
declare(strict_types=1);

namespace App\Application\Actions\Event;

use Psr\Http\Message\ResponseInterface as Response;

class ViewLightAction extends EventAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $eventId = (int) $this->resolveArg('id');
        $event = $this->repository->getActiveEvent($eventId);

        $this->logger->info("Event of id `${eventId}` was viewed.");
        return $this->respondWithData($event);
    }
}
