<?php

namespace Controller\Admin;

use Exception;
use Controller\Controller;
use App\Exception\FormException;
use Model\Manager\User\UserManager;

class AdminUserController extends Controller
{

    public function index()
    {
        $request = $this->getRequest();
        $flashMessage = $this->flashMessage($request);
        $flashError = $this->flashError($request);

        $userManager = $this->getDatabase()->getManager(UserManager::class);

        $users = $userManager->getUsers();

        return $this->render("admin/user/index.html.twig", ['users' => $users, 'flashMessage' => $flashMessage, 'flashError' => $flashError, 'tokenCSRF' => $request->getSession('tokenCSRF')]);
    }

    public function create()
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request, $userManager)) {
                $creationDate = date('Y-m-d H:i:s');
                $userManager->insertUser(
                    [
                        'username' => $request->postData('username'),
                        'mail' => $request->postData('mail') ? $request->postData('mail') : "",
                        'password' => password_hash($request->postData('password'), PASSWORD_BCRYPT, ["cost" => 12]),
                        'roles' => !empty($request->postData('roles')) ? json_encode($request->postData('roles')) : json_encode(["user"]),
                        'creationDate' => $creationDate,
                        'confirmationToken' => null
                    ]
                );
                $request->setSession('flashMessage', "Utilisateur ajouté");
                $this->redirect("admin_users");
            }
        } catch (FormException $e) {
            $errors[] = $e->getMessage();
        }
        return $this->render("admin/user/create.html.twig", [
            'errors' => $errors,
            'postDatas' => $request->postTableData() ? $request->postTableData() : null
        ]);
    }

    public function modify($id)
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        $user = $userManager->getUser($id);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request, $userManager, $id)) {
                $userManager->updateUser(
                    [
                        'username' => $request->postData('username'),
                        'mail' => $request->postData('mail') ? $request->postData('mail') : "",
                        'roles' => json_encode($request->postData('roles')),
                        'confirmationToken' => $user->getConfirmationToken(),
                        'isValidated' => $request->postData('isValidated')
                    ],
                    $id
                );
                $request->setSession('flashMessage', "Utilisateur $id modifié");
                $this->redirect("admin_users");
            }
        } catch (FormException $e) {
            $errors[] = $e->getMessage();
        }
        return $this->render("admin/user/modify.html.twig", [
            'errors' => $errors,
            'postDatas' => $request->postTableData() ? $request->postTableData() : $user,
            'userId' => $id
        ]);
    }

    public function delete($id)
    {
        $request = $this->getRequest();
        $this->checkCSRF($request);
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        try{
        $userManager->deleteUser($id);
        }catch(Exception $e){
            $request->setSession('flashError',"Problème lors de la suppression, assurez vous de supprimer les commentaires et les articles de l'utilisateur avant de supprimer celui-ci");
        }
        // we redirect to the previous page after delete
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function isValidForm($request, $userManager, $id = null): bool
    {
        $returnValue = true;
        $mail = $request->postData('mail');
        $password = $request->postData('password');
        $username = $request->postData('username');
        if (!$username || strlen($username) < 4) {
            throw new FormException('Pseudo trop court');
            $returnValue = false;
        }

        $user = $userManager->getUserByUsername($username);
        // if it is a modification we have to check if the username is in the database and different from the user id
        if ($id) {
            $users = $userManager->getUsersByUsername($username);
            if (count($users) > 1) {
                throw new FormException("Ce nom d'utilisateur existe déjà");
            }elseif (count($users) === 1 && (int)$users[0]->getId() !== (int)$id) {
                throw new FormException("Ce nom d'utilisateur existe déjà");
            }
        // if the user is created we just need to check that a user doesn't exists in the database with the username
        } elseif ($user) {
            throw new FormException("L'utilisateur existe déjà");
            $returnValue = false;
        }
        // we don't check the password value if it is a modification ($id exists)
        if (!$id && (!$password || strlen($password) < 4)) {
            throw new FormException('Mot de passe trop court');
            $returnValue = false;
        }
        if ($mail && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new FormException('Votre mail ne convient pas');
            $returnValue = false;
        }
        return $returnValue;
    }
}
