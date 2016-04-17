<?php

namespace Caponica\AmazonMwsBundle;

use CaponicaAmazonMwsComplete\MwsProductClient;

class MwsClientPool {
    /**
     * @var MwsProductClient
     */
    protected $mwsProductClient;
    protected $config;

    public function setConfig($config = []) {
        $requiredKeys = [
            'access_key',
            'secret_key',
            'application_name',
            'application_version',
            'config',
        ];

        foreach ($requiredKeys as $key) {
            if (empty($config[$key])) {
                throw new \InvalidArgumentException('Missing required configuration key (' . $key . ') for MwsClientPool');
            }
        }

        $this->config = $config;
    }

    public function getMwsProductClient() {
        if(empty($this->mwsProductClient)) {
            $this->mwsProductClient = new MwsProductClient(
                    $this->config['access_key'], 
                    $this->config['secret_key'], 
                    $this->config['application_name'], 
                    $this->config['application_version'], 
                    $this->config['config']
            );
        }
        
        return $this->mwsProductClient;
    }
    
}
