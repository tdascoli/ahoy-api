<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use App\Auth\JwtAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * JWT middleware.
 */
final class JwtMiddleware implements Middleware
{
    /**
     * @var JwtAuth
     */
    private $jwtAuth;

    public function __construct(JwtAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $authorization = explode(' ', (string)$request->getHeaderLine('Authorization'));
        $token = $authorization[1] ?? '';

        if (!$token || !$this->jwtAuth->validateToken($token)) {
            $response = new Response();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401, 'Unauthorized');
        }

        // Append valid token
        $parsedToken = $this->jwtAuth->createParsedToken($token);
        $request = $request->withAttribute('token', $parsedToken);

        // Append the user id as request attribute
        $request = $request->withAttribute('uid', $parsedToken->getClaim('uid'));

        return $handler->handle($request);
    }
}