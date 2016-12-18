<?php

//require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$enquete = $app['controllers_factory'];
$enquete->get('/',function(){
    return new Response("Acesso às enquetes");
});

$enquete->get('/mostra/{id}',function($id){
    return new Response("Mostrando enquete número: {$id}");
});

return $enquete;

