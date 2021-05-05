<?php

namespace MyBlog;

class Router{

    /**
     * @var Route[]
     */
    private array $routes = [];

    /**
     * @param Route $route
     * @return $this
     */
    public function add(Route $route): self{
        if($this->has($route->getName())){
            throw new RouteAlreadyExistsException('Route already exists');
        }

        // I add my route in the routes of my router
        $this->routes[$route->getName()] = $route;
        return $this;
    }
    
    /**
     * @param  string $name
     * @return Route
     * @throws RouteNotFoundException
     */
    public function get(string $name): ?Route{
        if(!$this->has($name)){
            throw new RouteNotFoundException('Route not found');
        }
        return $this->routes[$name];
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function has(string $name): bool{
        return isset($this->routes[$name]);
    }

    public function match(string $path): Route{
        foreach ($this->routes as $route) {
            if($route->testPath($path)){
                return $route;
            }
        }
        throw new RouteNotFoundException('Route not found');
        /* // If the path is not found, i return the homepage
        return $this->routes["home"]; */
    }

    public function call(string $path): Route{
        return $this->match($path)->call($path);
    }

    /**
     * @return array|Route[]
     */
    public function getRouteCollection(): array{

        return $this->routes;
    }
    
}