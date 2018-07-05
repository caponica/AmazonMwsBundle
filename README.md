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
    seller_id:           your_seller_id_de
    access_key:          your_access_key_de
    secret_key:          your_secret_key_de
    application_name:    your_app_name
    application_version: 1.0
    amazon_site:         DE
caponica_amazon_mws_config_uk:
    seller_id:           your_seller_id_uk
    access_key:          your_access_key_uk
    secret_key:          your_secret_key_uk
    application_name:    your_app_name
    application_version: your_app_version
    amazon_site:         UK
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

Often multiple marketplaces in the same region will share a single configuration. In these cases you can use the 
`siteCode` configuration parameter to re-use the same configuration:

``` yaml
# app/config/parameters.yml
caponica_amazon_mws_config_europe:
    seller_id:           your_seller_id_europe
    access_key:          your_access_key_europe
    secret_key:          your_secret_key_europe
    application_name:    your_app_name
    application_version: 1.0
    amazon_site:         DE # just set one valid site here
```

``` yaml
# services.yml
    caponica_mws_client_pool_de:
        class:      %caponica_amazon_mws.client_pool.class%
        arguments:
            - '@your_logger' # optional Logger, see caponica/amazon-mws-complete docs
        calls:
            - [ setConfig, [ %caponica_amazon_mws_config_europe%, 'DE' ]]
    caponica_mws_client_pool_uk:
        class:      %caponica_amazon_mws.client.class%
        calls:
            - [ setConfig, [ %caponica_amazon_mws_config_europe%, 'UK' ]]
```

Usage
-----

To access the service, just reference it by the service name you defined above. E.g., from a controller:

    /** @var CaponicaAmazonMwsComplete\ClientPool\MwsClientPool $mwsClientPoolUk */
    $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
    $mwsProductClientPackUk = $mwsClientPoolUk->getProductClientPack();
