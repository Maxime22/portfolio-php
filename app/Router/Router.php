<?php

namespace App\Router;

class Router
{

    private $httpRequest;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct($httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    public function get(string $path, $callable, string $routeName = null)
    {
        return $this->add($path, $callable, $routeName, 'GET');
    }

    public function post(string $path, $callable, string $routeName = null)
    {
        return $this->add($path, $callable, $routeName, 'POST');
    }

    public function add(string $path, $callable, string $routeName = null, string $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        // If callable is like "BlogController#action", we name the route like that
        if ($routeName === null && is_string($callable)) {
            $routeName = $callable;
        }
        if ($routeName) {
            $this->namedRoutes[$routeName] = $route;
        }
        return $route; // to chain the methods, not mandatory
    }

    public function run()
    {
        $httpMethod = $this->httpRequest->serverRequestMethod();
        // request method is get or post
        if (!isset($this->routes[$httpMethod])) {
            throw new RouterException('Route with the REQUEST_METHOD does not exist');
        }
        foreach ($this->routes[$httpMethod] as $route) {
            if ($route->match($this->httpRequest->getRequestURI())) {
                return $route->call($this->httpRequest, $this);
            }
        }
        throw new RouterException('No matching routes');
    }

    // to generate url in our code based on routeName and params
    public function url(string $routeName, array $params = [])
    {
        if (!isset($this->namedRoutes[$routeName])) {
            throw new RouterException('No route matches this name');
        }
        return $this->namedRoutes[$routeName]->getUrl($params);
    }
}
