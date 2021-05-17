<?php

namespace Controller\Home;

use Controller\Controller;

class HomeController extends Controller{

    public function index(){
        return $this->render("home/index.html.twig", []);
    }

}