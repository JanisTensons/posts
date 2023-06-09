<?php declare(strict_types=1);

namespace App;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\PdoArticleRepository;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;
use DI\ContainerBuilder;

class Router
{
    public static function response(): ?View
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ArticleRepository::class => new PdoArticleRepository(),
            UserRepository::class => new JsonPlaceholderUserRepository(),
        ]);
        $container = $builder->build();

        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', 'App\Controllers\ArticleController@home');
            $r->addRoute('GET', '/articles', 'App\Controllers\ArticleController@index');
            $r->addRoute('GET', '/article', 'App\Controllers\ArticleController@show');
            $r->addRoute('GET', '/user', 'App\Controllers\UserController@show');
            $r->addRoute('GET', '/users', 'App\Controllers\UserController@index');
        });

// Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                return null;

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                return null;

            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerName, $methodName] = explode('@', $handler);
                $controller = $container->get($controllerName);
                /** @var View $response */
                return $controller->{$methodName}();
        }
        return null;
    }
}