<?php

namespace App\Router;

use App\Request\HTTPRequest;
use App\Exception\ForbiddenException;

class Route
{
    private $path; // all the routes we create in index
    private $callable;
    private $matches = [];
    private $params = [];

    public function __construct($path, $callable)
    {
        $this->path = $path;
        $this->callable = $callable;
    }

    public function call(HTTPRequest $request, $router)
    {
        // If we request a controller, we call the controller
        if (is_string($this->callable)) {
            $explodeCallable = explode("#", $this->callable);
            $controllerPath = $this->createControllerPath($request, $explodeCallable);
            $action = $explodeCallable[1];
            $controller = new $controllerPath($request, $router);
            return call_user_func_array([$controller, $action], $this->matches);
        }
        // call callable
        return call_user_func_array($this->callable, $this->matches);
    }

    private function createControllerPath($request, $explodeCallable)
    {
        $controllerPath = "";
        if (str_contains($this->callable, "Admin")) {
            $controllerPath = $this->getAdminPath($request, $explodeCallable);
        } else {
            $explodeName = explode("Controller", $this->callable);
            $controllerPath = "Controller\\" . ucfirst($explodeName[0]) . "\\" . $explodeCallable[0];
        }
        return $controllerPath;
    }

    private function getAdminPath(HTTPRequest $request, $explodeCallable)
    {
        if ($request->getSession('auth') && in_array("admin", $request->getSession('userRoles'))) {
            return "Controller\\Admin\\" . $explodeCallable[0];
        } else {
            throw new ForbiddenException;
        }
    }

    public function match(string $url): bool
    {
        if (strlen($url) > 1) {
            $url = rtrim($url, '/');
        }
        // Manage inappropriate ? in url (facebook)
        if(str_contains($url,"?")){
            $explodedUrl = explode("?",$url);
            $url = $explodedUrl[0];
        }
        $regex = '/^' . preg_replace('/\//', '\/', $this->path) . '$/';
        $regex = preg_replace_callback('/\{(.+?)\}/', [$this, 'paramMatch'], $regex);
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    public function with($param, $regex)
    {
        $this->params[$param] = $regex;
        return $this; // allow to chain methods
    }

    // complexify the regex checking the "with" method, get and with are done before match, cause match is made in run method
    // $match is created by preg_replace_callback method
    public function paramMatch(array $match): string
    {
        // if we have with parameters
        if (isset($this->params[$match[1]])) {
            return $this->params[$match[1]];
        }
        // otherwise preg_replace_callback works like normal preg_replace
        return '(.+?)';
    }

    public function getUrl(array $params): string
    {
        $path = $this->path;
        foreach ($params as $k => $v) {
            $path = str_replace('{' . $k . '}', $v, $path);
        }
        return $path;
    }
}
