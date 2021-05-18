<?php

namespace Model\Manager\User;

use Model\Manager\Manager;
use Model\Entity\User\User;

class UserManager extends Manager
{

    public function getUserByUsername(string $username)
    {
        return $this->queryFetch(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate' FROM user WHERE username='" . $username . "'",
            User::class
        );
    }

    public function getUsersByUsername(string $username)
    {
        return $this->queryFetchAll(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate' FROM user WHERE username='" . $username . "'",
            User::class
        );
    }

    public function getUsers()
    {
        return $this->queryFetchAll(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate' FROM user",
            User::class
        );
    }

    public function getUser($id)
    {
        return $this->queryFetch(
            "SELECT id, username, mail, password, roles, creation_date as 'creationDate' FROM user WHERE id=$id",
            User::class
        );
    }

    public function insertUser(array $params)
    {
        $this->prepare(
            "INSERT INTO user (username, mail, password, roles, creation_date) VALUES (:username,:mail,:password, :roles, :creationDate)",
            User::class,
            $params
        );
    }

    public function updateUser(array $params, $id)
    {
        $this->prepare(
            "UPDATE user SET username = :username, mail = :mail, roles = :roles WHERE id = $id",
            BlogPost::class,
            $params
        );
    }

    public function deleteUser($id)
    {
        $this->delete("DELETE FROM user WHERE id = :id", $id);
    }

    public function getRolesById($id)
    {
        $this->queryFetch(
            "SELECT roles FROM user WHERE id=$id",
            User::class
        );
    }
}
