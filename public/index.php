<?php
require '../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use App\Request\HTTPRequest;
use App\Security\ForbiddenException;
use App\Router\RouterException;

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
// we start the session
$httpRequest->sessionStart();
// we add all the routes
include dirname(__DIR__) . '/app/routes.php';

// Against Session Hijacking
if (isset($_SESSION['ipAddress']) && ($_SERVER['REMOTE_ADDR'] !== $_SESSION['ipAddress'])) {
    session_unset();
    session_destroy();
}
if (isset($_SESSION['userAgent']) && ($_SERVER['HTTP_USER_AGENT'] !== $_SESSION['userAgent'])) {
    session_unset();
    session_destroy();
}
if (isset($_SESSION['lastAccess']) && (time() > ($_SESSION['lastAccess'] + 3600))) {
    session_unset();
    session_destroy();
} elseif (isset($_SESSION['lastAccess'])) {
    $httpRequest->setSession('lastAccess', time());
}

try {
    // render of a Controller return a response with the content
    $response = $router->run();
    // we display the content
    $response->send();
} catch (ForbiddenException $e) {
    header("Location: /login");
    exit();
} catch (RouterException $e) {
    header("Location: /error404");
    exit();
} catch (\Exception $e) {
    echo $e->getMessage();
}
