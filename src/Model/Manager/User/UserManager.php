<?php

namespace Model\Manager\User;

use Model\Manager\Manager;
use Model\Entity\User\User;

class UserManager extends Manager
{

    public function getUserByUsername(array $params)
    {
        return $this->queryFetch(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate', is_validated as 'isValidated' FROM user WHERE username= :username",
            User::class,
            $params
        );
    }

    public function getUsersByUsername(array $params)
    {
        return $this->queryFetchAll(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate', is_validated as 'isValidated' FROM user WHERE username= :username",
            User::class,
            $params
        );
    }

    public function getUsers()
    {
        return $this->queryFetchAll(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate', is_validated as 'isValidated' FROM user ORDER BY id DESC",
            User::class
        );
    }

    public function getUser(array $params)
    {
        return $this->queryFetch(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate', is_validated as 'isValidated' FROM user WHERE id = :id",
            User::class,
            $params
        );
    }

    public function getUserByConfirmationToken(array $params)
    {
        return $this->queryFetch(
            "SELECT id, username, mail, roles, confirmation_token as 'confirmationToken' FROM user WHERE confirmation_token = :confirmationToken",
            User::class,
            $params
        );
    }

    public function insertUser(array $params)
    {
        $this->prepare(
            "INSERT INTO user (username, mail, password, roles, creation_date, is_validated, confirmation_token) VALUES (:username,:mail,:password, :roles, :creationDate, 0, :confirmationToken)",
            User::class,
            $params
        );
    }

    public function updateUser(array $params, $id)
    {
        $this->prepare(
            "UPDATE user SET username = :username, mail = :mail, roles = :roles, confirmation_token = :confirmationToken, is_validated = :isValidated WHERE id = $id",
            BlogPost::class,
            $params
        );
    }

    public function deleteUser($id)
    {
        $this->delete("DELETE FROM user WHERE id = :id", $id);
    }
}
