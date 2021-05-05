<?php

include './src/Route.php';
include './src/Router.php';
include './src/RouteNotFoundException.php';
include './src/RouteAlreadyExistsException.php';
// we can put : namespace Myblog; if we put index.php in src I THINK (cause the autoload is linked to the src folder)

use MyBlog\Route;
use MyBlog\Router;

$route1 = new Route("home","/", function(){return "Hello World";});
$route2 = new Route("blogPosts","/blogPost", function(){return "Hello Blog";});
$route3 = new Route("blogPost","/blogPost/{id}/{slug}", function(string $slug, string $id){return sprintf("format : %s %s",$id,$slug);});

$router = new Router();

$path = '/blogPost/12/banane';

try {
    $router->add($route1);
    $router->add($route2);
    $router->add($route3);
} catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
}


var_dump($router->call($path));