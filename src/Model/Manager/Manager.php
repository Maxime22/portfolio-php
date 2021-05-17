<?php

namespace Model\Manager;

class Manager{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo=$pdo;
    }

    public function prepare(string $querySQL, string $class, $params=null){       
        if($params){
            $query = $this->pdo->prepare($querySQL);
            $query->execute($params);
        }else{
            $query = $this->pdo->query($querySQL);
            $query->setFetchMode(\PDO::FETCH_CLASS, $class);
            $query->execute();
        }
        return $query;
    }

    public function queryFetchAll(string $querySQL, string $class)
    {
        $datas = $this->prepare($querySQL, $class)->fetchAll();
        return $datas;
    }

    public function queryFetch(string $querySQL, string $class)
    {
        $datas = $this->prepare($querySQL, $class)->fetch();
        return $datas;
    }

    public function insert(string $querySQL, string $class, $params=null)
    {
        $this->prepare($querySQL, $class, $params);
    }

}