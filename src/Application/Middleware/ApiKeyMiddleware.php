<?php
declare(strict_types=1);

namespace App\Application\Middleware;


use App\Auth\ApiKeyAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class ApiKeyMiddleware implements Middleware
{

    /**
     * @var ApiKeyAuth
     */
    private $apiKeyAuth;

    public function __construct(ApiKeyAuth $apiKeyAuth)
    {
        $this->apiKeyAuth = $apiKeyAuth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = explode(',', (string)$request->getHeaderLine('Authorization'));
        $apikey = $authorization[1] ?? '';

        if (!$apikey || !$this->apiKeyAuth->validate($apikey)) {
            $response = new Response();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401, 'Unauthorized');
        }

        return $handler->handle($request);
    }
}