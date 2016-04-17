Amazon MWS Bundle
=================

Amazon MWS integration via a Symfony service.

Installation
------------

Install using [composer](http://getcomposer.org) by adding the following in the `require` section of your `composer.json` file:

``` json
    "require": {
        ...
        "caponica/amazon-mws-bundle": "dev-master"
    },
```

Register the bundle in your kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Caponica\AmazonMwsBundle\CaponicaAmazonMwsBundle(),
    );
}
```


Configuration
-------------

The bundle does not add a service to your project by default. To add the service,
you will need to define the parameters and then the service itself.

To access multiple marketplaces, create multiple services (one for each marketplace).
The simplest way to do this is with parameters (as shown below), but you can use any
method you like to load the relevant configuration parameters and pass them to setConfig.

``` yaml
# app/config/parameters.yml
caponica_amazon_mws_config_de:
    access_key:          your_access_key_de
    secret_key:          your_secret_key_de
    application_name:    your_app_name
    application_version: 1.0
    config:
        ServiceURL: 'https://mws-eu.amazonservices.com/Products/2011-10-01'
caponica_amazon_mws_config_uk:
    access_key:          your_access_key_uk
    secret_key:          your_secret_key_uk
    application_name:    your_app_name
    application_version: your_app_version
    config:
        ServiceURL: 'https://mws.amazonservices.co.uk/Products/2011-10-01'
```

``` yaml
# services.yml
    caponica_mws_client_pool_de:
        class:      %caponica_amazon_mws.client_pool.class%
        calls:
            - [ setConfig, [ %caponica_amazon_mws_config_de% ]]
    caponica_mws_client_pool_uk:
        class:      %caponica_amazon_mws.client.class%
        calls:
            - [ setConfig, [ %caponica_amazon_mws_config_uk% ]]
```


Usage
-----

To access the service, just reference it by the service name you defined above. E.g., from a controller:

    /** @var \Caponica\AmazonMwsBundle\MwsClientPool $mwsClientPoolUk */
    $mwsClientPoolUk = $this->getContainer()->get('caponica_mws_client_pool_uk');
    $mwsProductClientUk = $mwsClientPoolUk->getMwsProductClient();
