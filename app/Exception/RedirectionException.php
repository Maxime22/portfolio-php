<?php

namespace App\Exception;

class RedirectionException extends \Exception
{

    private $routeName;
    private $params;

    public function __construct($routeName, $params)
    {
        parent::__construct();
        $this->routeName = $routeName;
        $this->params = $params;
    }

    public function getRouteName(){
        return $this->routeName;
    }

    public function getParams(){
        return $this->params;
    }
}
