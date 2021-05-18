<?php

namespace Controller\Authentification;

use Controller\Controller;

class AuthentificationController extends Controller
{

    public function login()
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        if ($request->postTableData()) {
            $username = $request->postTableData()['username'];
            $user = $userManager->getUserByUsername($username);
        }

        if (isset($user) && $this->checkPassword($request->postTableData()['username'], $user->getPassword())) {
            $request->setSession('auth', $user->getId());
            $this->redirect("admin");
        }
        return $this->render("/login.html.twig", []);
    }

    public function checkPassword($password, $databasePassword)
    {
        return password_verify($password, $databasePassword);
    }
}
