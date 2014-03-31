<?php

namespace App\Core\Authenticator;

use Symfony\Component\HttpFoundation\Request;

class Loader
{
    public static function getAuthenticator($app, $controller, $config,Request $request)
    {
        $extraTypes = $config['extra'];
        $keys = array_keys($extraTypes);
        foreach($keys as $key){
            if(array_search($controller, $extraTypes[$key], true) !== false){
                return self::create($key, $app, $request);
            }
        }
        
        return self::create($config['default'], $app, $request);
    }
    
    protected static function create($authName, $app, $request)
    {
        $authName = '\App\Core\Authenticator\\'.$authName;
        if (!class_exists($authName)) {
            throw new Exception("Authenticator $authName not found!");
        }

        return new $authName($app, $request);
    }
}