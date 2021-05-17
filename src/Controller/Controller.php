<?php

namespace Controller;

use App\Database\Database;
use App\Response\Response;
use App\Request\HTTPRequest;

class Controller
{
    private $twig;
    private $database;
    private $request;

    public function __construct(HTTPRequest $request)
    {
        $this->request=$request;
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__).DIRECTORY_SEPARATOR.'View');
        $this->twig = new \Twig\Environment($loader, array(
            'cache' => false,
        ));
        // we create a session variable for twig
        $this->database = new Database();
    }

    protected function render($fileName, $data = [])
    {
        return new Response($this->twig->load($fileName)->render($data));
    }

    public function getDatabase(){
        return $this->database;
    }

    public function getRequest(){
        return $this->request;
    }

    public function flashMessage($request){
        $flashMessage = $request->getSession('flashMessage');
        // we want to display the message only one time
        $request->unsetSession('flashMessage');
        return $flashMessage;
    }

}
