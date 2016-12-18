<?php
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once (__DIR__."/../vendor/autoload.php");

$app = new Silex\Application();
$app->register(new TwigServiceProvider(),array(
    'twig.path' => __DIR__.'/../view/'
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

//habilitar debug:
$app['debug'] =true;

//
$data = [
   // 'nome' => 'Silex',
    'curso' => 'SON'
];

//maneira antiga
//$data = array(
//    'nome' => 'Silex',
//    'curso' => 'SON'
//);

$app->get('/hello',function() use($data)
{
    return "<h3>Olá,{$data['nome']} aí!</h3>";
});

$app->get('/blog/{id}',function($id) use($data)
{
   return $id;
});

$app->get('/aplica',function(Silex\Application $app) use($data)
{
    if (!isset($data['nome'])) {
        $app->abort('Abortando, não encontrou o nome',412);
    }
});

$cli = [
    'clientes' =>[
        [
            'nome' => 'Jovair',
            'email' => 'jovair@hotmail.com',
            'cpf' => '000.111.222.-62'
        ],
        [
            'nome' => 'Nelson',
            'email' => 'nelson@hotmail.com',
            'cpf' => '002.131.229.-33'
        ],
        [
            'nome' => 'Richard',
            'email' => 'richard@hotmail.com',
            'cpf' => '220.115.242.-09'
        ],
    ]
];
$gr = 'p';
$before = function(Request $request)use($gr){
    if ($gr == '') {
        return new RedirectResponse('/cliente');
    }
};

$app->get('/',function()use ($app){
    return $app['twig']->render('index.twig',[]);
})->before($before);

$app->get('/cliente',function()use($cli,$app){
    return $app->json($cli);
});

//response
//id - id do artigo
//idComentario - id do comentário
//precisa colocar como parametro da função anônima para pode usar no corpo da página
//para usar variáveis do Silex tem que colocar na função anônima também
$app->get('/artigo/{id}/{idComentario}',function(Silex\Application $app, Request $request,$id,$idComentario){

});

$app->get('/',function() use ($app,$gr){
    return '<h3>Esta é a página inicial</h3><p>Gr é:'.$gr.'</p>';
});

/*
 * Rodar alguma coisa antes das rotas: - before
 * no caso só vai mostrar na tela com echo - se usar return não vai mostrar
 * se quiser se antecipar às exceções usar:Silex\Application::EARLY_EVENT
 */

//bind nomeia uma rota
$app->get('/clientes',function()use($app){
    return "Estou na página de clientes";
})->bind('clientes');

$app->run();