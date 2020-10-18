<?php
declare(strict_types=1);

namespace App\Application\Actions\Profile;

use Psr\Http\Message\ResponseInterface as Response;

class ListAction extends ProfileAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $list = $this->repository->list();

        $this->logger->info("Profiles list was viewed.");

        return $this->respondWithData($list);
    }
}
