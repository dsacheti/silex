<?php
require_once 'vendor/autoload.php';

use Silex\Provider\TwigServiceProvider;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new TwigServiceProvider(),array(
    'twig.path' => __DIR__.'/view/'
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());