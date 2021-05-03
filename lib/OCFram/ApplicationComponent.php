<?php
/* get the right application for the objects, for example HTTPRequest is a child of ApplicationComponent which use the Application $app */
namespace OCFram;

abstract class ApplicationComponent
{
  protected $app;
  
  public function __construct(Application $app)
  {
    $this->app = $app;
  }
  
  public function app()
  {
    return $this->app;
  }
}