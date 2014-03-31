<?php

namespace App\Core\Authenticator;

class PrivateKey extends Authenticator
{
    /**
     * Runs basic authentication procedure based in private key
     * @throws \Api\Core\Authenticator\Exception
     */
    public function run()
    {
        $generatedHash = $this->getHash();

        if ($generatedHash != $this->hash) {
            throw new Exception('AUTH_INVALID_HASH_MESSAGE', 'AUTH_INVALID_HASH_CODE');
        }
    }
    
    protected function getHash()
    {
        return $this->hash();
    }
    
}
