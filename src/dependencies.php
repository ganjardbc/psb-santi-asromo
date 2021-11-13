<?php
// DIC configuration
use Slim\Views\PhpRenderer;
$container = $app->getContainer();

// view renderer
// $container['renderer'] = function ($c) {
//     $settings = $c->get('settings')['renderer'];
//     return new Slim\Views\PhpRenderer($settings['template_path']);
// };

// monolog
// $container['logger'] = function ($c) {
//     $settings = $c->get('settings')['logger'];
//     $logger = new Monolog\Logger($settings['name']);
//     $logger->pushProcessor(new Monolog\Processor\UidProcessor());
//     $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
//     return $logger;
// };

$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'].";charset=utf8;",$settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
};

$container['dbSiakad'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=103.101.225.93;dbname=santiasr_prod;charset=utf8;",'monty', '@Qwerty90');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
};

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('views', [
        'cache' => false
    ]);  
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    $view->addExtension(new \DPolac\TwigLambda\LambdaExtension());
    $view->addExtension(new \Teraone\Twig\Extension\StrftimeExtension());

    return $view;
};
