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
        $authorization = explode(' ', (string)$this->request->getHeaderLine('Authorization'));
        $token = $authorization[1] ?? '';
        $deviceId = $this->jwtAuth->getUID($token);

        $profileId = (int) $this->resolveArg('profile_id');
        $events = array();
        if ($this->auth->verifyDeviceIdWithProfileId($deviceId, $profileId)) {
            $events = $this->repository->listByProfileId($profileId);
            $this->logger->info("Events list was viewed.");
        }

        return $this->respondWithData($events);
    }
}
