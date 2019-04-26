<?php

require_once 'Doctrine/ORM/Tools/Setup.php';
// Setup Autoloader (1)
Doctrine\ORM\Tools\Setup::registerAutoloadPEAR();

// configuration (2)
$config = new \Doctrine\ORM\Configuration();

// Proxies (3)
$config->setProxyDir(__DIR__ . '\Doctrine\Proxy');
$config->setProxyNamespace('Doctrine\Proxy');

$config->setAutoGenerateProxyClasses(true);

// Driver (4)
$driverImpl = new Doctrine\ORM\Mapping\Driver\YamlDriver(array(__DIR__ . '/../application/configs/schema'));
$config->setMetadataDriverImpl($driverImpl);

// Caching Configuration (5)
$cache = new \Doctrine\Common\Cache\ArrayCache();

$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

$connectionOptions = array(
    'data_fixtures_path' => __DIR__ . '/../application/configs/data/fixtures',
    'models_path' => __DIR__ . '/../application/models/doctrine/models',
    'migrations_path' => __DIR__ . '/../application/configs/migrations',
    'sql_path' => __DIR__ . '/../application/configs/data/sql',
    'yaml_schema_path' => __DIR__ . '/../application/configs/schema',
    'driver' => 'pdo_mysql',
    'user' => 'vlmis',
    'password' => 'v123lmis',
    'dbname' => 'vlmis_zr4',
    'host' => '192.168.1.72');

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
$platform = $em->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');
$platform->registerDoctrineTypeMapping('tinyblob', 'string');
\Doctrine\DBAL\Types\Type::addType('BlobType', 'Doctrine\DBAL\Types\BlobType');
$platform->registerDoctrineTypeMapping('blob','BlobType');

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
        ));
