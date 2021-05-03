<?php
namespace OCFram;

class Router
{
  protected $routes = [];

  const NO_ROUTE = 1;

  public function addRoute(Route $route)
  {
    if (!in_array($route, $this->routes))
    {
      $this->routes[] = $route;
    }
  }

  public function getRoute($url)
  {
    foreach ($this->routes as $route)
    {
      // If the route matches with the url (use preg_match function)
      if (($varsValues = $route->match($url)) !== false)
      {
        // If there are variables
        if ($route->hasVars())
        {
          $varsNames = $route->varsNames();
          $listVars = [];

          // (key = variable name, $match = value)
          foreach ($varsValues as $key => $match)
          {
            // We need to not use the first key cause preg_match use the first key to have all the datas in one string
            if ($key !== 0)
            {
              $listVars[$varsNames[$key - 1]] = $match;
            }
          }

          // We put the var table in the route
          $route->setVars($listVars);
        }

        return $route;
      }
    }

    throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
  }
}