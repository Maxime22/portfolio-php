<?php

namespace Model\Manager;

class Manager{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo=$pdo;
    }

}