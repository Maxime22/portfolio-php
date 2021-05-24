<?php

namespace Controller\Authentification;

use App\Mailer\Mailer;
use Controller\Controller;
use App\Exception\FormException;
use Model\Manager\User\UserManager;

class AuthentificationController extends Controller
{

    public function login()
    {
        $request = $this->getRequest();
        // errors from otherPages
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        // If the user is already connected, we don't want him to go to the login page
        $user = $this->checkAuth($request, $userManager);

        $errors = [];
        // If he sent params, we check the password
        if ($request->postTableData()) {
            $username = $request->postTableData()['username'];
            $user = $userManager->getUserByUsername(["username" => $username]);
            
            // redirection is made here if all is ok
            $this->managePostDatas($request, $user);
            $errors = $this->checkErrors($user, $request);
        }
        return $this->render("/login.html.twig", ["errors" => $errors, "flashError" => $this->flashError($request), "flashMessage" => $this->flashMessage($request)]);
    }

    private function managePostDatas($request, $user)
    {
        if ($user !== false && $this->checkPassword($request->postTableData()['password'], $user->getPassword())) {
            if ($user->getIsValidated()) {
                $this->setSessions($request, $user);
                $this->checkAdminRoleAndRedirection($user);
            }
        }
    }
    private function checkErrors($user, $request)
    {
        $errors = [];
        if (!$user->getIsValidated()) {
            $errors[] = "Vous devez valider votre mail";
        }
        if ($user === false || !$this->checkPassword($request->postTableData()['password'], $user->getPassword())) {
            $errors[] = "Identifiant ou mot de passe incorrect";
        }
        return $errors;
    }

    private function checkAuth($request, $userManager)
    {
        if ($request->getSession('auth')) {
            $user = $userManager->getUser(["id" => $request->getSession('auth')]);
            $this->checkAdminRoleAndRedirection($user);
            return $user;
        }
        return null;
    }

    private function checkAdminRoleAndRedirection($user)
    {
        if (in_array("admin", $user->getRoles())) {
            $this->redirect("admin");
        } else {
            $this->redirect("home");
        }
    }

    public function setSessions($request, $user)
    {
        $request->setSession('auth', $user->getId());
        $request->setSession('userRoles', $user->getRoles());
        // Session Hijacking
        $request->setSession('ipAddress', $_SERVER['REMOTE_ADDR']);
        $request->setSession('userAgent', $_SERVER['HTTP_USER_AGENT']);
        $request->setSession('lastAccess', time());
        // Token CSRF for each user
        $request->setSession('tokenCSRF', bin2hex(random_bytes(16)));
    }

    public function subscription()
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidSubscriptionForm($request, $userManager)) {
                $this->sendSubscriptionMail($userManager, $request);
            }
        } catch (FormException $e) {
            $errors[] = $e->getMessage();
        }

        return $this->render("/subscription.html.twig", ["errors" => $errors]);
    }

    public function sendSubscriptionMail($userManager, $request)
    {
        $mailer = new Mailer();
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        $token = substr(str_shuffle(str_repeat($alphabet, 12)), 0, 12);
        $tokenLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/subscriptionValidation/' . $token;
        $userManager->insertUser(
            [
                'username' => $request->postData('username'),
                'mail' => $request->postData('mail') ? $request->postData('mail') : "",
                'password' => password_hash($request->postData('password'), PASSWORD_BCRYPT, ["cost" => 12]),
                'roles' => !empty($request->postData('roles')) ? json_encode($request->postData('roles')) : json_encode(["user"]),
                'creationDate' => date('Y-m-d H:i:s'),
                'confirmationToken' => $token
            ]
        );
        $message = (new \Swift_Message('Email pour valider votre compte'))
            ->setFrom([$_ENV['MAIL_USERNAME']])
            ->setTo([$request->postData('mail')])
            ->setBody('Pour valider votre compte, cliquez sur le lien suivant : ' . $tokenLink);

        // Send the message
        $mailer->send($message);
        $request->setSession('flashMessage', "Un mail de confirmation vous a été envoyé");
        $this->redirect('login');
    }

    public function subscriptionValidation($token)
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        $user = $userManager->getUserByConfirmationToken(["confirmationToken" => $token]);
        if ($user) {
            $userManager->updateUser(
                [
                    'username' => $user->getUsername(),
                    'mail' => $user->getMail(),
                    'roles' => json_encode($user->getRoles()),
                    'confirmationToken' => null,
                    'isValidated' => 1
                ],
                $user->getId()
            );
            $request->setSession('flashMessage', "Inscription validée, vous pouvez vous connecter");
        } else {
            $request->setSession('flashError', "Le token envoyé n'est pas valide");
        }
        $this->redirect("login");
    }

    public function checkPassword($password, $databasePassword)
    {
        return password_verify($password, $databasePassword);
    }

    public function isValidSubscriptionForm($request, $userManager): bool
    {
        $mail = $request->postData('mail');
        $password = $request->postData('password');
        $passwordValidation = $request->postData('passwordValidation');
        $username = $request->postData('username');

        if (!$username || strlen($username) < 4) {
            throw new FormException('Pseudo trop court');
        }

        $user = $userManager->getUserByUsername(["username" => $username]);
        if ($user) {
            throw new FormException("L'utilisateur existe déjà, veuillez choisir un autre identifiant");
        }
        if (!$password || strlen($password) < 4) {
            throw new FormException('Mot de passe trop court');
        }
        if ($password !== $passwordValidation) {
            throw new FormException('Les deux mots de passe ne sont pas identiques');
        }
        if ($mail && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new FormException('Votre mail ne convient pas');
        }
        return true;
    }

    public function logout()
    {
        $request = $this->getRequest();
        $request->unsetSession('auth');
        session_destroy();
        $this->redirect('login');
    }
}
