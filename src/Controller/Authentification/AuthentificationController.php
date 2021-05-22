<?php

namespace Controller\Authentification;

use App\Mailer\Mailer;
use Exception;
use Controller\Controller;
use Model\Manager\User\UserManager;

class AuthentificationController extends Controller
{

    public function login()
    {
        $request = $this->getRequest();
        // errors from otherPages
        $flashError = $this->flashError($request);
        $flashMessage = $this->flashMessage($request);
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        // If the user is already connected, we don't want him to go to the login page
        if ($request->getSession('auth')) {
            $user = $userManager->getUser($request->getSession('auth'));
            if (in_array("admin", $user->getRoles())) {
                $this->redirect("admin");
            } else {
                $this->redirect("home");
            }
        }

        // If he sent params, we check the password
        $errors = [];
        if ($request->postTableData()) {
            $username = $request->postTableData()['username'];
            $user = $userManager->getUserByUsername($username);
            if ($user !== false && $this->checkPassword($request->postTableData()['password'], $user->getPassword())) {
                if ($user->getIsValidated()) {
                    $request->setSession('auth', $user->getId());
                    $request->setSession('userRoles', $user->getRoles());
                    // Session Hijacking
                    $request->setSession('ipAddress', $_SERVER['REMOTE_ADDR']);
                    $request->setSession('userAgent', $_SERVER['HTTP_USER_AGENT']);
                    $request->setSession('lastAccess', time());
                    // Token CSRF for each user
                    $request->setSession('tokenCSRF', bin2hex(random_bytes(16)));

                    if (in_array("admin", $user->getRoles())) {
                        $this->redirect("admin");
                    } else {
                        $this->redirect("home");
                    }
                } else {
                    $errors[] = "Vous devez valider votre mail";
                }
            } else {
                $errors[] = "Identifiant ou mot de passe incorrect";
            }
        }

        return $this->render("/login.html.twig", ["errors" => $errors, "flashError" => $flashError, "flashMessage" => $flashMessage]);
    }

    public function subscription()
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidSubscriptionForm($request, $userManager)) {
                $creationDate = date('Y-m-d H:i:s');
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
                        'creationDate' => $creationDate,
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
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $this->render("/subscription.html.twig", ["errors" => $errors]);
    }

    public function subscriptionValidation($token)
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        $user = $userManager->getUserByConfirmationToken($token);
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
        $returnValue = true;
        $mail = $request->postData('mail');
        $password = $request->postData('password');
        $passwordValidation = $request->postData('passwordValidation');
        $username = $request->postData('username');

        if (!$username || strlen($username) < 4) {
            throw new Exception('Pseudo trop court');
            $returnValue = false;
        }

        $user = $userManager->getUserByUsername($username);
        if ($user) {
            throw new Exception("L'utilisateur existe déjà, veuillez choisir un autre identifiant");
            $returnValue = false;
        }
        if (!$password || strlen($password) < 4) {
            throw new Exception('Mot de passe trop court');
            $returnValue = false;
        }
        if ($password !== $passwordValidation) {
            throw new Exception('Les deux mots de passe ne sont pas identiques');
            $returnValue = false;
        }
        if ($mail && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Votre mail ne convient pas');
            $returnValue = false;
        }
        return $returnValue;
    }
}
