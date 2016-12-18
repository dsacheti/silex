<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once (__DIR__."/../vendor/autoload.php");

$app = new Silex\Application();
//habilitar debug:
$app['debug'] =true;

/*
 * Conrtoller de enquetes
 */

$enquete = $app['controllers_factory'];

$enquete->get('/',function(){
    return "Acesso à enquete";
});

$enquete->get('/mostrar',function(){
    return "Exibir uma enquete";
});

/*
 * Controller do fórum
 */

$forum = $app['controllers_factory'];

$forum->get('/',function(){
    return new Response("Acesso ao fórum");
});

/*
 * montando as rotas para enquete e forum
 */
$app->mount('/enquete',$enquete);
$app->mount('/forum',$forum);

$app->run();