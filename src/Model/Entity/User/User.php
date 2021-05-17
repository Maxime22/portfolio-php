<?php

namespace Model\Entity\User;

class User
{

    private $id;
    private $firstName;
    private $lastName;
    private $mail;
    private $password;
    private $creationDate;
    private $roles;

    // GETTERS
    public function getId()
    {
        return $this->id;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function getLastName()
    {
        return $this->lastName;
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
