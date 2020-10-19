<?php
declare(strict_types=1);

namespace App\Application\Actions\Event;

use Psr\Http\Message\ResponseInterface as Response;

class ViewAction extends EventAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $authorization = explode(' ', (string)$this->request->getHeaderLine('Authorization'));
        $token = $authorization[1] ?? '';
        $deviceId = $this->jwtAuth->getUID($token);

        $eventId = (int) $this->resolveArg('id');
        $event = $this->repository->get($eventId);
        $profileId = $event->getProfileId();

        if ($this->auth->verifyDeviceIdWithProfileId($deviceId, $profileId)) {
            $this->logger->info("Event of id `${eventId}` was viewed.");
            return $this->respondWithData($event);
        }
        else {
            return $this->respondWithData("No Event found", 404);
        }
    }
}
