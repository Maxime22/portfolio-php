<?php
namespace App\Request;

class HTTPRequest
{
  public function getData($key)
  {
    return $_GET[$key] ?? null;
  }

  public function postData($key)
  {
    return $_POST[$key] ?? null;
  }

  public function serverRequestMethod(){
      return $_SERVER['REQUEST_METHOD'];
  }

  public function getRequestURI()
  {
    return $_SERVER['REQUEST_URI'];
  }
}