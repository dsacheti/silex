<?php

//require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$forum = $app['controllers_factory'];

$forum->get('/',function(){
    return new Response("Índice do Fórum");
});

return $forum;