<?php

require_once __DIR__.'/../vendor/autoload.php';

//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;


//$app['parametro'] = 'valor';//parametro da aplicação
//
////declaração de um serviço
//$app['pdo'] = function(){
//  return new \PDO('dsn','user','senha');  
//};
////definindo outro serviço e usando um serviço definido
//$app['pessoa'] = function()use ($app){
//    $pdo = $app['pdo'];//o serviço define um objeto
//    return new Pessoa($pdo);
//};
////criando o objeto baseado no serviço - instanciação de serviço
//$pessoa = $app['pessoa'];

//SERVIÇO
//$app['res'] = function(){
//    return new Response('OI');
//};

//SERVIÇO COMPARTILHADO
//usando app->share permite que a aplicação verifique se existe uma
//instancia e retorne o objeto já instaciado a cada criação de objeto
$app['res'] = $app->share(function(){
    return new Response('OI');
});

$res1 = $app['res'];
$res2 = $app['res'];//esses dois objetos são diferentes, pois embora tenham
//as mesmas propriedades, são criados novos objetos em cada linha
//porém o $app->share deixa iguais, pois vai retornar a mesma instancia

$app->mount('/enquete',include 'enquete.php');
$app->mount('/forum', include 'forum.php');

$app->run();