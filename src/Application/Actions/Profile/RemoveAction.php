<?php
declare(strict_types=1);

namespace App\Application\Actions\Profile;

use Psr\Http\Message\ResponseInterface as Response;

class RemoveAction extends ProfileAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        $uid = (int) $this->resolveArg('uid');
        $this->logger->info("try to remove Profile ${uid}.");

        $success = $this->repository->remove($uid);
        $this->logger->info("Profile Set ${success}.");

        $this->logger->info("Profile Set ${uid}.");

        return $this->respondWithData($success);
    }
}