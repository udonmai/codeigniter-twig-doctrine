<?php

class Doctrine {

  public $em = null;

  public function __construct()
  {
    // load database configuration from CodeIgniter
    require_once APPPATH.'config/database.php';

    // Set up class loading. You could use different autoloaders, provided by your favorite framework,
    // if you want to.
    require_once APPPATH.'libraries/Doctrine/Common/ClassLoader.php';

    $doctrineClassLoader = new \Doctrine\Common\ClassLoader('Doctrine',  APPPATH.'libraries');
    $doctrineClassLoader->register();
	//$symfonyClassLoader = new \Doctrine\Common\ClassLoader('Symfony', APPPATH.'libraries/Doctrine');
	//$symfonyClassLoader->register();
	$entitiesClassLoader = new \Doctrine\Common\ClassLoader('Entities', APPPATH.'models');
    $entitiesClassLoader->register();
    $proxiesClassLoader = new \Doctrine\Common\ClassLoader('Proxies', APPPATH.'models');
    $proxiesClassLoader->register();

    //Set up config
	$config = new \Doctrine\ORM\Configuration;

	//Cache || ENVIRONMENT is defined in /index.php
	if(ENVIRONMENT == 'development')
		$cache = new \Doctrine\Common\Cache\ArrayCache;
	else
		$cache = new \Doctrine\Common\Cache\ApcCache;
	
    $config->setMetadataCacheImpl($cache);
    $config->setQueryCacheImpl($cache);

	//Metadata driver
	$driverImpl = $config->newDefaultAnnotationDriver(array(APPPATH.'models/Entities'));
    $config->setMetadataDriverImpl($driverImpl);
    //set up Yaml driver
	//$yamlDriver = new \Doctrine\ORM\Mapping\Driver\YamlDriver(APPPATH.'models/Mappings');
	//$config->setMetadataDriverImpl($yamlDriver);

    // Proxy configuration
    $config->setProxyDir(APPPATH.'/models/Proxies');
    $config->setProxyNamespace('Proxies');

    // Set up logger
    //$logger = new \Doctrine\DBAL\logging\EchoSQLLogger;
    //$config->setSQLLogger($logger);

	if (ENVIRONMENT == "development") {
		$config->setAutoGenerateProxyClasses(TRUE);
	} else {
		$config->setAutoGenerateProxyClasses(FALSE);
	}
	
    // Database connection information
    $connectionOptions = array(
        'driver' => 'pdo_mysql',
        'user' =>     $db['default']['username'],
        'password' => $db['default']['password'],
        'host' =>     $db['default']['hostname'],
        'dbname' =>   $db['default']['database']
    );

    // Create EntityManager
    $this->em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
  }
}
