<?php
require_once __DIR__.'/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager as EventManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache as Cache;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\ClassLoader;

use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use MicroFrame\Entity\User;

$cache = new Cache;

$annotationReader = new AnnotationReader;
$cachedAnnotationReader = new Doctrine\Common\Annotations\CachedReader(
	$annotationReader,
	$cache
);

$driverChain = new Doctrine\ORM\Mapping\Driver\DriverChain();
//carrega a superclasse de mapeamento de metadata somente no driver chain
//registra Gedmo annotations.NOTE: você pode personaliazar
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
	$driverChain,
	$cachedAnnotationReader
);

//agora nós queremos registrar as entidades da aplicação
//para isso nós precisamos de outro driver da metadados usado para o namespace das entidades
$annotationDriver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
	$cachedAnnotationReader,
	array(__DIR__.DIRECTORY_SEPARATOR.'../src')
);

$driverChain->addDriver($annotationDriver,'MicroFrame');

$config = new Configuration;
$config->setProxyDir('/tmp');
$config->setProxyNamespace('Proxy');
$config->setAutoGenerateProxyClasses(true);

//registrando driver de metadados
$config->setMetadataDriverImpl($driverChain);

//usar o driver de cache já inicializado
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

AnnotationRegistry::registerFile(__DIR__.DIRECTORY_SEPARATOR.'../vendor'. DIRECTORY_SEPARATOR . 'doctrine' . DIRECTORY_SEPARATOR . 'orm' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Doctrine' . DIRECTORY_SEPARATOR . 'ORM' . DIRECTORY_SEPARATOR . 'Mapping' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'DoctrineAnnotations.php');

// Third, create event manager and hook prefered extension listeners
$evm = new Doctrine\Common\EventManager();
// gedmo extension listeners

// sluggable
$sluggableListener = new Gedmo\Sluggable\SluggableListener;
// you should set the used annotation reader to listener, to avoid creating new one for mapping drivers
$sluggableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($sluggableListener);


//getting the EntityManager
$entityManager = EntityManager::create(
    array(
        'driver'  => 'pdo_mysql',
        'host'    => 'localhost',
        'port'    => '3306',
        'user'    => 'homestead',
        'password'  => 'secret',
        'dbname'  => 'silex_bd',
    ),
    $config,
    $evm
);

$app = new Silex\Application();
$app['debug'] = true;

//depois do código abaixo ao chamar $app['user_repository'] vai gerar e retornar o $repo
$app['user_repository'] = $app->share(function($app)use ($entityManager){
    $user = new User();
    
    $repo = $entityManager->getRepository('MicroFrame\Entity\User');
    $repo->setPasswordEncoder($app['security.encoder_factory']->getEncoder($user));
    return $repo;
});

$app->register(new SessionServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'anonymous' => true,
            'pattern' => '^/',
            'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            // lazily load the user_repository
            'users' => $app->share(function () use ($app) {
                return $app['user_repository'];
            }),
            'logout' => array('logout_path' => '/admin/logout'),
        ),
    )
));
// access controls - quem pode acesssar
$app['security.access_rules'] = array(
    //a rota que inicia com admin só pode ser acessada qeum tem ROLE_ADMIN
    array('^/admin', 'ROLE_ADMIN'),
);

$app->register(new Silex\Provider\TwigServiceProvider(),array(
    'twig.path' => __DIR__.'/../view/',//pasta de templates do twig    
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

