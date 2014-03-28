<?php
namespace App\Core;

use App\Provider;
use Symfony\Component\HttpKernel\Log\NullLogger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class Application  extends \Silex\Application
{
    public function __construct($env, $basePath) 
    {
        parent::__construct();
        $this['basePath'] = $basePath;

        $this->loadHttpBase();
        $this->loadConfig($env);
        $this->loadMonolog();
        $this->loadValidation();
        $this->loadControllers();
    }
    
    protected function loadHttpBase()
    {
        $this['http.request'] = Request::createFromGlobals();
        $this['http.response'] = new Response('', 200, array('Cache-Control' => 's-maxage=120', 'ETag' => uniqid()));
    }
    
    protected function loadConfig($env)
    {
        $this->register(new Provider\Config(), array(
            'config.file' => $this['basePath']."/config/config.$env.ini"
        ));
    }
    
    protected function loadMonolog()
    {        
        $config = $this['config'];
        
        $log = $config['log'];
        if(!$log['enable']){
            return $this['monolog'] = new NullLogger();
        }
        
        $this->register(new \Silex\Provider\MonologServiceProvider(), array(
            'monolog.logfile' => $this['basePath'].$log['file']
        ));
    }
    
    protected function loadAuthenticator()
    {
        $this->register(new \Api\Provider\Authenticator(), array(
            'auth.config' => $this['basePath'].'/config/authenticator.json'
        ));
    }

    protected function loadValidation()
    {
        $this->register(new \Silex\Provider\ValidatorServiceProvider());
    }
    
    protected function loadControllers()
    {
        $this->mount('/', new Provider\Controller());
    }
}
