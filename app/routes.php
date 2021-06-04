<?php
declare(strict_types=1);

use App\Application\Actions\Message\DeleteMessageAction;
use App\Application\Actions\Message\MessageListAction;
use App\Application\Actions\Message\NewMessageAction;
use App\Application\Actions\Message\RecentMessageAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\AddUserAction;
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
        $response->getBody()->write('The API is up and running!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->post('', AddUserAction::class);
    });

    $app->group('/chat', function (Group $group) {
        $group->delete('/{id}', DeleteMessageAction::class);
        $group->get('/{id}', MessageListAction::class);
        $group->get('/{id}/{timestamp}', RecentMessageAction::class);
        $group->post('', NewMessageAction::class);
    });

};
