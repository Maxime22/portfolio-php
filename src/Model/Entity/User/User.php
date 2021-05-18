<?php

namespace Model\Entity\User;

class User
{

    private $id;
    private $username;
    private $mail;
    private $password;
    private $creationDate;
    private $roles;

    public function __construct()
    {
        // This line is done just after the hydratation of the fetch class
        $this->roles = json_decode($this->roles, true);
    }

    // GETTERS
    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getMail()
    {
        return $this->mail;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    public function getRoles()
    {
        return $this->roles;
    }
}
