<?php
declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Application\Actions\Action;
use App\Auth\AuthService;
use App\Auth\JwtAuth;
use App\Domain\Event\EventRepository;
use Psr\Log\LoggerInterface;

abstract class EventAction extends Action
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * @var JwtAuth
     */
    protected $jwtAuth;

    /**
     * @var AuthService
     */
    protected $auth;

    /**
     * @param LoggerInterface $logger
     * @param EventRepository  $repository
     * @param JwtAuth $jwtAuth
     * @param AuthService $auth
     */
    public function __construct(LoggerInterface $logger, EventRepository $repository, JwtAuth $jwtAuth, AuthService $auth)
    {
        parent::__construct($logger);
        $this->repository = $repository;
        $this->jwtAuth = $jwtAuth;
        $this->auth = $auth;
    }
}
