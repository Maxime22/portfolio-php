<?php

namespace Controller;

use App\Response\Response;
use App\Database\Database;

class Controller
{
    private $twig;
    private $database;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__).DIRECTORY_SEPARATOR.'View');
        $this->twig = new \Twig\Environment($loader, array(
            'cache' => false,
        ));
        $this->database = new Database();
    }

    protected function render($fileName, $data = [])
    {
        return new Response($this->twig->load($fileName)->render($data));
    }

    public function getDatabase(){
        return $this->database;
    }

}
