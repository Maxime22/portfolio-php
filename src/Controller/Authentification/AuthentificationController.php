<?php

namespace Controller\Authentification;

use Controller\Controller;

class AuthentificationController extends Controller{

    public function login(){
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        return $this->render("/index.html.twig", []);
    }
    
}