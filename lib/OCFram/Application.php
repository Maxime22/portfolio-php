<?php
/* abstract class which will run the application, the other class will heritate this one */
namespace OCFram;

abstract class Application
{
  protected $httpRequest;
  protected $httpResponse;
  protected $name;
  protected $user;
  protected $config;
  
  public function __construct()
  {
    $this->httpRequest = new HTTPRequest($this);
    $this->httpResponse = new HTTPResponse($this);
    $this->name = '';
    $this->user = new User($this);
    $this->config = new Config($this);
  }
  
  abstract public function run();
  
  public function httpRequest()
  {
    return $this->httpRequest;
  }
  
  public function httpResponse()
  {
    return $this->httpResponse;
  }
  
  public function name()
  {
    return $this->name;
  }

  public function user()
  {
    return $this->user;
  }

  public function config()
  {
    return $this->config;
  }


  public function getController()
  {
    $router = new Router;

    $xml = new \DOMDocument;
    $xml->load(__DIR__.'/../../App/'.$this->name.'/Config/routes.xml');

    $routes = $xml->getElementsByTagName('route');

    // We go through xml routes
    foreach ($routes as $route)
    {
      $vars = [];

      // We check variables in the URL
      if ($route->hasAttribute('vars'))
      {
        $vars = explode(',', $route->getAttribute('vars'));
      }

      // We add the route to the router
      $router->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
    }

    try
    {
      // We get the right route linked to the URL
      $matchedRoute = $router->getRoute($this->httpRequest->requestURI());
    }
    catch (\RuntimeException $e)
    {
      if ($e->getCode() == Router::NO_ROUTE)
      {
        // If nothing exist, we redirect
        $this->httpResponse->redirect404();
      }
    }

    // We add URL variables to the $_GET table
    $_GET = array_merge($_GET, $matchedRoute->vars());

    // We instanciate the right controller
    $controllerClass = 'App\\'.$this->name.'\\Modules\\'.$matchedRoute->module().'\\'.$matchedRoute->module().'Controller';
    return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
  }
}