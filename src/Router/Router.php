<?php

namespace MyBlog\Router;

class Router
{

    private $url;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct($url)
    {
        $this->url = $url;
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
        if($routeName === null && is_string($callable)){
            $routeName = $callable;
        }
        if ($routeName) {
            $this->namedRoutes[$routeName] = $route;
        }
        return $route; // to chain the methods, not mandatory
    }

    public function run()
    {
        // request method is get or post
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }
        throw new RouterException('No matching routes');
    }

    // to generate url in our code based on routeName and params
    public function url(string $routeName,array $params = [])
    {
        if (!isset($this->namedRoutes[$routeName])) {
            throw new RouterException('No route matches this name');
        }
        return $this->namedRoutes[$routeName]->getUrl($params);
    }
}
