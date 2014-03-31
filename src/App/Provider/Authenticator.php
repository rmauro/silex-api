<?php

namespace App\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Authenticator implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['authenticator'] = $app->protect(function($controller) use ($app){
            return \App\Core\Authenticator\Loader::getAuthenticator($app, $controller, \Zend\Config\Factory::fromFile($app['auth.config']), Request::createFromGlobals());
        });
    }
    
    public function boot(Application $app)
    {
    }
    
}