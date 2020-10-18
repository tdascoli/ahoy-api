<?php
declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Auth\AuthService;
use App\Auth\JwtAuth;
use App\Domain\Auth\AccountRepository;
use Psr\Log\LoggerInterface;

abstract class AuthAction extends Action
{
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
     * @param JwtAuth $jwtAuth
     * @param AuthService $auth
     */
    public function __construct(LoggerInterface $logger, JwtAuth $jwtAuth, AuthService $auth)
    {
        parent::__construct($logger);
        $this->jwtAuth = $jwtAuth;
        $this->auth = $auth;
    }
}
