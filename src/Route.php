<?php

namespace MyBlog;

use ReflectionFunction;
use ReflectionParameter;

class Route{

    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $path;
    /**
     * @var array|callable
     */
    private $callable;

    public function __construct(string $name, string $path, $callable)
    {
        $this->name = $name;
        $this->path = $path;
        $this->callable = $callable;
    }

    public function getName(){
        return $this->name;
    }

    public function getPath(){
        return $this->path;
    }

    public function testPath(string $path): bool{
        // We create a regex to test the path (/blogPost/{id} for example) and get the parameters
        $regex = str_replace("/","\/",$this->path);
        // We add the / at the beginning and at the end to create a regex, %s means string
        $regex = sprintf("/^%s$/",$regex);
        var_dump($regex);
        // The () groups the elements, preg_replace do it for all the matches, + means one or more occurence are mandatory
        $regex = preg_replace("/\{(.+?)\}/","(.+)",$regex);
        var_dump($regex);
        // we check if the path we sent corresponds to the path of the route pattern ($regex)
        return preg_match($regex,$path);
    }

    public function call(string $path)
    {
        $regex = str_replace("/","\/",$this->path);
        $regex = sprintf("/^%s$/",$regex);
        $regex = preg_replace("/\{(.+?)\}/","(.+)",$regex);
        // We recreate the regex to get the matches this time
        preg_match_all($regex,$path,$matches);
        // We need to delete the first result in matches which has all the datas
        array_shift($matches);

        // results in the $this->path (id and slug)
        preg_match_all("/\{(\w+)\}/",$this->path,$paramMatches);
        $parameters = $paramMatches[1];

        // results in the $path (12 and banane)
        $arrayMatches=[];
        for($i=0;$i<count($matches);$i++) {
            $arrayMatches[$parameters[$i]]=$matches[$i][0];
        }
        var_dump($arrayMatches);

        //A PARTIR DE LA JE COMPRENDS RIEN
        /* if(count($parameters)>0){
            $parameters = array_combine($parameters,$matches);

            $reflectionFunc = new ReflectionFunction($this->callable);
            
            $argsValue = [];

            $args = array_map(fn(ReflectionParameter $param)=>$param->getName(),$reflectionFunc->getParameters());
            $argsValue = array_map(
                    function(string $name) use ($parameters){
                        return $parameters[$name];
                    }
                    ,$args
                );
        } */
        // on était pas censé renvoyé juste les matches au lieu de argsValue?
        //return call_user_func_array($this->callable,$argsValue);

        // first parameter = the function we want to call, other parameters = args i want in the function of the first parameter
        return call_user_func_array($this->callable,$arrayMatches);
        
        
    }

}