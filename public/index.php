<?php
require '../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__).'/.env');

// WHOOPS WORKS FOR EXCEPTIONS, WE SHOULD COMMENT THIS CODE IN PRODUCTION
if($_ENV['APP_ENV']==='dev'){
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

// https://openclassrooms.com/fr/courses/1665806-programmez-en-oriente-objet-en-php/1668568-developpement-de-la-bibliotheque

$uri = $_SERVER['REQUEST_URI'];
$router = new MyBlog\Router\Router($uri);

// When i have the URL /post, i want to display the echo
$router->get('/', function(){ echo 'Homepage'; },'home');
$router->get('/blogPosts', function(){ echo 'Tous les articles'; },'blogPosts');
// $router->get('/blogPosts/{id}/{slug}', function($id,$slug)use($router){ echo $router->url('blogPosts_show',['id'=>$id,'slug'=>$slug]); },'blogPosts_show');
// we need to send $id and $slug in the same order in the url and in the callable
/* $router->get('/blogPosts/{id}', function($id){ ?>

    <form action="" method="POST">
    <input type="text" name="name">
    <button type="submit">Envoyer</button>
    </form>
    <?php
}, 'blogPost')->with('id','([0-9]+)')->with('slug','([a-z\-0-9]+)'); */
$router->get('/blogPosts/{id}/{slug}','BlogPostController#show')->with('id','([0-9]+)')->with('slug','([a-z\-0-9]+)'); ;
$router->post('/blogPosts/{id}/{slug}', function($id,$slug){ echo 'Poster pour l\'article '.$id .'<pre>'.print_r($_POST).'</pre>'; },'blogPost_post');

$router->run();