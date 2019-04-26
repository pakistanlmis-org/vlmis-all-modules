<?php
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once 'Doctrine/ORM/Tools/Setup.php';
require_once 'Doctrine/Common/ClassLoader.php';

define('APPLICATION_ENV', "development");
error_reporting(E_ALL);

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', 'Doctrine');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__ . '/application/models/doctrine/models');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__ . '\Doctrine\Proxy');
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setProxyDir('Doctrine/Proxy');
$config->setProxyNamespace('Doctrine\Proxy');
$config->setAutoGenerateProxyClasses(true);

$config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));


 //Here is the part that needs to be adjusted to make allow the ORM namespace in the annotation be recognized

#$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__ . "/application/persistent/Entities"));

//AnnotationRegistry::registerFile("Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
//$reader = new AnnotationReader();
//$driverImpl = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, array(__DIR__ . '/../application/models/doctrine/models'));
//$config->setMetadataDriverImpl($driverImpl);

$driverImpl = new Doctrine\ORM\Mapping\Driver\YamlDriver(array(__DIR__ . '/../application/configs/schema'));
$config->setMetadataDriverImpl($driverImpl);

//$config = Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration(array(__DIR__ . '/../application/configs/schema'), true);
//End of Changes

if (APPLICATION_ENV == "development") {
    $cache = new \Doctrine\Common\Cache\ArrayCache();
} else {
   $cache = new \Doctrine\Common\Cache\ApcCache();
}

$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

$connectionOptions = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'vlmis',
    'user'     => 'root',
    'password' => ''
);

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

$helperSet = ($helperSet) ? : new \Symfony\Component\Console\Helper\HelperSet();

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);