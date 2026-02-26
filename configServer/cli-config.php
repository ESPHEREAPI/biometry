<?php
//Insert in config folder

require_once "vendor/autoload.php";

// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;


// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array(__DIR__."/../module/Entities");
$isDevMode = true;

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

// database configuration parameters
$conn = array(
    'driver'    => 'pdo_mysql',
    'host'      => 'localhost',
    'port'      => '3306',
    'user'      => 'root',
    'password'  => 'DeepWater@2021',
    'dbname'    => 'biometry',
    'charset'   => 'utf8',
    'driverOptions' => [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ]
);

$entityManager = EntityManager::create($conn, $config);

return ConsoleRunner::createHelperSet($entityManager);
