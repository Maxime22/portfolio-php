<?php

namespace Controller;

class Controller
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__).DIRECTORY_SEPARATOR.'View');
        $this->twig = new \Twig\Environment($loader, array(
            'cache' => false,
        ));
    }

    protected function render($fileName, $data = [])
    {
        return $this->twig->load($fileName)->render($data);
    }
}
