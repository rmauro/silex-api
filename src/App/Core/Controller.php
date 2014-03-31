<?php

namespace App\Core;

class Controller 
{
    protected $app;
    /**
     * @var \Symfony\Component\HttpFoundation\Request 
     */
    protected $request;
    
    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;
    protected $session;
    
    /**
     * @var \Container\Container
     */
    protected $container;

    public function __construct(\Silex\Application $app)
    {
        $this->app      = $app;
        $this->request  = $app['http.request'];
        $this->response = $app['http.response'];
    }
    
}
