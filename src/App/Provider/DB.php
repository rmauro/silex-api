<?php

namespace App\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class DB implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['db_connection'] = $app->protect(function($config) use ($app){
            new \Zend\Db\Adapter\Adapter($config);    
        });
    }
    
    public function boot(Application $app)
    {
    }
    
}