<?php

namespace App\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Core;
use Symfony\Component\HttpFoundation\Response;

class Controller implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $appController = $this;

        $app->match('/{controller}/{parts}',
                        function ($controller, $parts) use ($app, $appController) {
                            $controller = $appController->getController($controller, $app);
                            return $appController->execute($controller, $parts);
                        })
                ->assert('parts', '.*')
                ->convert('parts',
                        function ($parts, $request) {
                            return explode('/', $parts);
                        });

        $app->error(function(\Exception $e, $code) use ($app) {
            
            $response = $app['http.response'];
            $response->setContent($e->getMessage());
            $response->setStatusCode(400);
            return $response;
        });

        return $controllers;
    }

    public function getController($name, Application $app)
    {
        if (!class_exists($name)) {
            throw new InvalidControllerException("Controller $name not found!");
        }

        if (!is_subclass_of($name, '\App\Core\Controller')) {
            throw new InvalidControllerException("$name is not a valid controller!");
        }
        
        $authenticator = $app['authenticator']($name);
        //$authenticator->run();
        
        return new $name($app);
    }

    public function execute(Core\Controller $controller, $parts = array())
    {
        if (!sizeof($parts)) {
            throw new InvalidActionException("Invalid action");
        }

        $action = array_shift($parts);

        if (!method_exists($controller, $action)) {
            throw new InvalidActionException("Action $action does not exist!");
        }

        $response = $controller->$action();
        return new Response(json_encode($response), 200, array('Cache-Control' => 's-maxage=120', 'ETag' => uniqid()));
    }

}