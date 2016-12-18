<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../public/bootstrap.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
));

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);