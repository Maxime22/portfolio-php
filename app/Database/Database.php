<?php

namespace App\Database;

use Model\Manager\Manager;

class Database
{

    private $pdo;
    private $manager;

    public function __construct()
    {
        $this->pdo = new \PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    public function getManager(string $managerName)
    {
        $this->manager = new $managerName($this->pdo);
        return $this->manager;
    }
}
