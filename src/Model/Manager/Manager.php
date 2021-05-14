<?php

namespace Model\Manager;

class Manager{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo=$pdo;
    }

    public function queryBuilder(string $querySQL, string $class){
        $query = $this->pdo->query($querySQL); 
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $query;
    }

    public function queryFetchAll(string $querySQL, string $class)
    {
        $datas = $this->queryBuilder($querySQL, $class)->fetchAll();
        return $datas;
    }

    public function queryFetch(string $querySQL, string $class)
    {
        $datas = $this->queryBuilder($querySQL, $class)->fetch();
        return $datas;
    }

}