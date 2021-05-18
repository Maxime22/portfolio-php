<?php

namespace Controller;

use App\Router\Router;
use App\Database\Database;
use App\Response\Response;
use App\Request\HTTPRequest;

class Controller
{
    private $twig;
    private $database;
    private $request;
    private $router;

    public function __construct(HTTPRequest $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'View');
        $this->twig = new \Twig\Environment($loader, array(
            'cache' => false,
        ));
        $this->database = new Database();
    }

    protected function render($fileName, $data = [])
    {
        return new Response($this->twig->load($fileName)->render($data));
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function flashMessage($request)
    {
        $flashMessage = $request->getSession('flashMessage');
        // we want to display the message only one time
        $request->unsetSession('flashMessage');
        return $flashMessage;
    }

    protected function redirect(string $routeName, $params = [])
    {
        header("Location: " . $this->router->url($routeName, $params));
        exit();
    }
}
