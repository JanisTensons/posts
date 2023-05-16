<?php declare(strict_types=1);

namespace App;

class Router
{
    public static function response(): ?View
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', 'App\Controllers\ArticleController@getIndex');
            $r->addRoute('GET', '/articles', 'App\Controllers\ArticleController@getArticlesContents');
            $r->addRoute('GET', '/article', 'App\Controllers\ArticleController@getArticleContents');
            $r->addRoute('GET', '/user', 'App\Controllers\ArticleController@getUserContents');
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
                $controller = new $controllerName;
                /** @var View $response */
                $response = $controller->{$methodName}();
                return $response;
        }
        return null;
    }
}