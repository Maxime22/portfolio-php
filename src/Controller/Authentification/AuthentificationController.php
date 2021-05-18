<?php

namespace Controller\Authentification;

use Controller\Controller;
use Model\Manager\User\UserManager;

class AuthentificationController extends Controller
{

    public function login()
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        // If the user is already connected, we don't want him to go to the login page
        if($request->getSession('auth')){
            $user = $userManager->getUser($request->getSession('auth'));
            if (in_array("admin", $user->getRoles())) {
                $this->redirect("admin");
            }else{
                $this->redirect("home");
            }
        }

        // If he sent params, we check the password
        $errors = [];
        if ($request->postTableData()) {
            $username = $request->postTableData()['username'];
            $user = $userManager->getUserByUsername($username);
            if ($user !== false && $this->checkPassword($request->postTableData()['password'], $user->getPassword())) {
                $request->setSession('auth', $user->getId());
                $request->setSession('roles', $user->getRoles());
                if (in_array("admin", $user->getRoles())) {
                    $this->redirect("admin");
                }else{
                    $this->redirect("home");
                }
            } else {
                $errors[] = "Identifiant ou mot de passe incorrect";
            }
        }

        return $this->render("/login.html.twig", ["errors" => $errors]);
    }

    public function checkPassword($password, $databasePassword)
    {
        return password_verify($password, $databasePassword);
    }
}
