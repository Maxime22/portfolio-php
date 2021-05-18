<?php

namespace Controller\Home;

use Controller\Controller;

class HomeController extends Controller{

    public function index(){
        return $this->render("home/index.html.twig", []);
    }

    public function error404(){
        http_response_code(404);
        return $this->render("home/e404.html.twig", []);
    }

}