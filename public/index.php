<?php
//require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/bootstrap.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


$app->get('/ola/{nome}',function($nome) use ($app,$entityManager){
    return $app['twig']->render('ola.twig',array('nome'=>$nome));
})->value('nome','Marcia')->bind('ola');

$app->get('/link_dinamico',function() use ($app){
    return $app['twig']->render('link.twig');
})->bind('link');

$app->post('/cadastrar',function(Silex\Application $app,Request $request)use($entityManager){
    $data = $request->request->all();
    $post = new \MicroFrame\Entity\Post();
    $post->setTitulo($data['titulo']);
    $post->setConteudo($data['conteudo']);
    $entityManager->persist($post);
    $entityManager->flush();

    if ($post->getId()) {
        return $app->redirect($app['url_generator']->generate('sucesso'));
    } else {
        return $app->abort(500,'Erro ao cadastrar');
    }
})->bind('cadastrar');

$app->get('/sucesso',function() use ($app){
    return $app['twig']->render('sucesso.twig',array());
})->bind('sucesso');

$app->get('/criaAdmin',function() use ($app){
    $repo = $app['user_repository'];
    $repo->createAdminUser('admin','admin');
    return new Response('Usuário administrador criado',200);
});

$app->get('/login',function(Request $request)use($app){
    return $app['twig']->render('login.twig',array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));

})->bind('login');



$app->get('/',function() use ($app) {
    return $app['twig']->render('index.twig', array(
        'username' => $app['security']->getToken()->getUser()
    ));
});

$app->run();