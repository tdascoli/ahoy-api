<?php
declare(strict_types=1);

namespace App\Application\Actions\Profile;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateAction extends ProfileAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        $uid = (int) $this->resolveArg('uid');

        $profile = $this->repository->post($this->getFormData(),$uid);
        $profile_id = $profile->getUid();
        $this->logger->info("Update Profile ${profile_id}.");

        return $this->respondWithData($profile);
    }
}