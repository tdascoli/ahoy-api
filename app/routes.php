<?php
declare(strict_types=1);

use App\Application\Actions\Auth\ProfileTokenAction;
use App\Application\Middleware\JwtMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    // Auth
    $app->group('/auth', function (Group $group) {
        $group->post('/profile', ProfileTokenAction::class);
    });

    $app->group('/profiles', function (Group $group) {
        $group->put('', \App\Application\Actions\Profile\PostAction::class);

        $group->put('/{id}', \App\Application\Actions\Profile\UpdateAction::class)->add(JwtMiddleware::class);
        $group->get('/{id}', \App\Application\Actions\Profile\ViewAction::class)->add(JwtMiddleware::class);
    });

    $app->group('/event', function (Group $group) {
        $group->put('', \App\Application\Actions\Event\PostAction::class);
        $group->get('/{id}', \App\Application\Actions\Event\ViewAction::class);
        $group->get('/list/{profile_id}', \App\Application\Actions\Event\ListAction::class);
    })->add(JwtMiddleware::class);

    $app->group('/events', function (Group $group) {
        $group->get('/{id}', \App\Application\Actions\Event\ViewLightAction::class);
    });

    $app->group('/queue', function (Group $group) {
        $group->put('/{event_id}', \App\Application\Actions\Queue\PostAction::class);
        $group->get('/{event_id}', \App\Application\Actions\Queue\ListAction::class)->add(JwtMiddleware::class);
        $group->delete('/{id}', \App\Application\Actions\Queue\RemoveAction::class)->add(JwtMiddleware::class);
    });
};
