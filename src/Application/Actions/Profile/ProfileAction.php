<?php
declare(strict_types=1);

namespace App\Application\Actions\Profile;

use App\Application\Actions\Action;
use App\Domain\Profile\ProfileRepository;
use Psr\Log\LoggerInterface;

abstract class ProfileAction extends Action
{
    /**
     * @var ProfileRepository
     */
    protected $repository;

    /**
     * @param LoggerInterface $logger
     * @param ProfileRepository $repository
     */
    public function __construct(LoggerInterface $logger, ProfileRepository $repository)
    {
        parent::__construct($logger);
        $this->repository = $repository;
    }
}
