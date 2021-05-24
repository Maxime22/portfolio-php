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
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        $users = $userManager->getUsers();

        return $this->render("admin/user/index.html.twig", ['users' => $users, 'flashMessage' => $this->flashMessage($request), 'flashError' => $this->flashError($request), 'tokenCSRF' => $request->getSession('tokenCSRF')]);
    }

    public function create()
    {
        $request = $this->getRequest();
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request, $userManager)) {
                $userManager->insertUser(
                    [
                        'username' => $request->postData('username'),
                        'mail' => $request->postData('mail') ? $request->postData('mail') : "",
                        'password' => password_hash($request->postData('password'), PASSWORD_BCRYPT, ["cost" => 12]),
                        'roles' => !empty($request->postData('roles')) ? json_encode($request->postData('roles')) : json_encode(["user"]),
                        'creationDate' => date('Y-m-d H:i:s'),
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
        $this->redirect("admin_users");
    }

    public function isValidForm($request, $userManager, $id = null): bool
    {
        $mail = $request->postData('mail');
        $password = $request->postData('password');
        $username = $request->postData('username');
        if (!$username || strlen($username) < 4) {
            throw new FormException('Pseudo trop court');
        }

        $this->checkIfUserExists($userManager, $username , $id);
        // we don't check the password value if it is a modification ($id exists)
        if (!$id && (!$password || strlen($password) < 4)) {
            throw new FormException('Mot de passe trop court');
        }
        if ($mail && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new FormException('Votre mail ne convient pas');
        }
        return true;
    }

    private function checkIfUserExists($userManager, $username , $id){
        $user = $userManager->getUserByUsername($username);
        // if it is a modification we have to check if the username is in the database and different from the user id
        if ($id!==null) {
            $users = $userManager->getUsersByUsername($username);
            $userCount = count($users);
            $firstUserId = (int)$users[0]->getId();
            if (($userCount > 1) || ($userCount === 1 && $firstUserId !== (int)$id)) {
                throw new FormException("Ce nom d'utilisateur existe déjà");
            }
        // if the user is created we just need to check that a user doesn't exists in the database with the username
        }
        if ($user) {
            throw new FormException("L'utilisateur existe déjà");
        }
    }
}
