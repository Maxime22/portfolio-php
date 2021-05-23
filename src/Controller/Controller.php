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
        $this->twig->addGlobal('auth', $request->getSession('auth'));
        $this->twig->addGlobal('userRoles', $request->getSession('userRoles'));
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

    public function flashMessage(HTTPRequest $request)
    {
        $flashMessage = $request->getSession('flashMessage');
        // we want to display the message only one time
        $request->unsetSession('flashMessage');
        return $flashMessage;
    }

    public function flashError(HTTPRequest $request)
    {
        $flashError = $request->getSession('flashError');
        // we want to display the message only one time
        $request->unsetSession('flashError');
        return $flashError;
    }

    protected function redirect(string $routeName, $params = [])
    {
        exit(header("Location: " . $this->router->url($routeName, $params)));
    }

    protected function redirect404()
    {
        exit(header("Location: /error404"));
    }

    protected function checkCSRF(HTTPRequest $request){
        $tokenCSRF = $request->postData('tokenCSRF');
        // We check if the user in the admin is the same as the one who was previously connected
        if (($tokenCSRF === null) ||($request->getSession('tokenCSRF') !== $tokenCSRF)) {
            $request->setSession('flashError', "Le token CSRF est invalide, vous ne pouvez pas supprimer le blogPost");
            exit(header('Location: ' . $_SERVER['HTTP_REFERER']));
        }
    }
}
