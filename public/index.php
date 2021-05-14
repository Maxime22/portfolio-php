<?php
require '../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use App\Request\HTTPRequest;

$httpRequest = new HTTPRequest();

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

// WHOOPS WORKS FOR EXCEPTIONS, WE SHOULD COMMENT THIS CODE IN PRODUCTION
if ($_ENV['APP_ENV'] === 'dev') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$router = new App\Router\Router($httpRequest);
// we add all the routes
include dirname(__DIR__).'/app/routes.php';

try {
    // render of a Controller return a response with the content
    $response = $router->run();
    // we display the content
    $response->send();
} catch (\Exception $e) {
    echo $e->getMessage();
}