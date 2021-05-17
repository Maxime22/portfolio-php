<?php

namespace App\Request;

class HTTPRequest
{
  public function getData($key)
  {
    return $_GET[$key] ?? null;
  }

  public function postTableData()
  {
    return $_POST ?? null;
  }

  public function postData($key)
  {
    return $_POST[$key] ?? null;
  }

  public function serverRequestMethod()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public function getRequestURI()
  {
    return $_SERVER['REQUEST_URI'];
  }

  public function getAllSession()
  {
    return $_SESSION;
  }

  public function getSession($key)
  {
    return $_SESSION[$key] ?? null;
  }

  public function setSession($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public function sessionStart()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function unsetSession($key)
  {
    unset($_SESSION[$key]);
  }
}
