<?php

declare(strict_types=1);

use App\ContainerDefinitions;
use App\Environment;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $builder, Environment $environment) {
    // file and folder paths
    $builder->addDefinitions(
        [
            ContainerDefinitions::PATH_ROOT => fn (): string => dirname(__DIR__),
            ContainerDefinitions::PATH_SRC_MODEL => fn (): string => dirname(__DIR__) . '/src/Application/Model',
            ContainerDefinitions::PATH_LOGS => fn (): string => dirname(__DIR__) . '/logs',
        ]
    );

    // add parameters based on environment
    $builder->addDefinitions(
        [
            ContainerDefinitions::APP_ENV => $environment,
            ContainerDefinitions::APP_DEBUG => DI\env('APP_DEBUG'),
            ContainerDefinitions::DATABASE_HOST => DI\env('DB_HOST'),
            ContainerDefinitions::DATABASE_NAME => DI\env('DB_DATABASE'),
            ContainerDefinitions::DATABASE_USER => DI\env('DB_USER'),
            ContainerDefinitions::DATABASE_PASSWORD => DI\env('DB_USER_PASSWORD'),
            ContainerDefinitions::DATABASE_PORT => DI\env('DB_PORT'),
        ]
    );

    // add main services
    $builder->addDefinitions(
        [
            Environment::class => function (ContainerInterface $container): Environment {
                return Environment::create($container->get(ContainerDefinitions::APP_ENV));
            },
            EntityManagerInterface::class => DI\get(EntityManager::class),
            EntityManager::class => function (ContainerInterface $container): EntityManager {
                $config = ORMSetup::createAttributeMetadataConfiguration(
                    [
                        $container->get(ContainerDefinitions::PATH_SRC_MODEL),
                    ],
                    true,
                );

                $databaseParameters = [
                    'driver' => 'pdo_mysql',
                    'host' => $container->get(ContainerDefinitions::DATABASE_HOST),
                    'dbname' => $container->get(ContainerDefinitions::DATABASE_NAME),
                    'user' => $container->get(ContainerDefinitions::DATABASE_USER),
                    'password' => $container->get(ContainerDefinitions::DATABASE_PASSWORD),
                    'charset' => 'utf8',
                ];
                $databaseParameters = DriverManager::getConnection($databaseParameters, $config);
                $entityManager = new EntityManager($databaseParameters, $config);

                return $entityManager;
            },
        ]
    );
};
