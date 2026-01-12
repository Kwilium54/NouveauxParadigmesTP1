<?php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../../../vendor/autoload.php';

// Chargement des variables d'environnement depuis prat.env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', 'prat.env');
$dotenv->load();

$mappingPath = [__DIR__ . '/../../Mapping'];
$isDevMode = true;

// Paramètres de connexion à la base de données
$dbParams = [
    'driver'   => 'pdo_pgsql',
    'host'     => 'praticien.db',
    'dbname'   => $_ENV['POSTGRES_DB'],
    'user'     => $_ENV['POSTGRES_USER'],
    'password' => $_ENV['POSTGRES_PASSWORD'],
    'port'     => 5432,
];

$config = ORMSetup::createXMLMetadataConfiguration($mappingPath, $isDevMode);
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);
