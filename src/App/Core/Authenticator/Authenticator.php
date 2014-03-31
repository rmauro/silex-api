<?php

namespace App\Core\Authenticator;

abstract class Authenticator
{
    public $app;
    public $request;
    public $data;
    public $publicKey;
    public $hash;
    
    abstract function run();
    
    public function __construct($app, $request)
    {
        $this->app = $app;
        $this->request = $request;

        $this->data         = json_decode($request->get('json-data'), true);
        $this->publicKey    = $request->get('public-key');
        $this->hash         = $request->get('hash');
        
        $this->setOutput();
    }
    
    public function hash($key = '')
    {
        /**
         * Authentication code goes here. Recover priv-key using $this->publicKey
         */
        $serverKey = [
            'private' => 'priv-key',
            'public' => 'pub-key'
        ];
        
        if (!($serverKey)) {
            throw new Exception('AUTH_INVALID_PUBLIC_KEY_MESSAGE');
        }

        return hash_hmac('sha256', $this->request->get('json-data'), $serverKey['private'].$key);
    }

    protected function setOutput()
    {
        $this->app['auth.user'] = null;
    }
}
