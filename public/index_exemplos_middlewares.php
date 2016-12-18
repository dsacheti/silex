<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

//$app->get('/artigos/{id}/{nome',function($id,$nome){
 //   if($id== 0){
  //      return new Response("O id é inválido",200);//se o id for zero, não temos artigo
  //  }
  //  return new Response("Acorda {$nome}:{$id}",200);
//})->convert('id',function($id){return (int)$id;})//se não passar um número fica converte para zero
//->value('nome','Dalicnei');//a variável nome, por padrão vai receber Dalcinei
//->assert('id','\d+');//só aceita dígitos positivos - retorna route not found
$app->get('/app/{nome}',function($nome){//se deixar a rota somente com parâmetro outras rotas poderão não funcionar
    return new Response("Acorda{$nome}",200);
})->value('nome','Dalcinei')
        ->bind('rota_nome');//nomeando uma rota->reutilização

$app->get('/json',function() use($app){
    $array = array('nome'=>'Dalcinei');
    return $app->json($array);//retornar um json ou streaming
});

//Middlewares de aplicação
$app->before(function(Request $request){//antes da request
    //return "Rodou antes";//return não mostra nada
    echo "Rodou antes";
}, Silex\Application::EARLY_EVENT);

$app->after(function(Request $request,Response $response){//depois da request, antes do response
    echo "Eu vou atrás";
}, Silex\Application::LATE_EVENT);

$app->finish(function(Request $request,Response $response){//depois do response
    echo "Eu por último e posso fazer log";
});

$app->run();