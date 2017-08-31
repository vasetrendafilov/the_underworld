<?php

session_cache_limiter(false);
session_start();

require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App([
  'settings'=>[
      'displayErrorDetails' => true,
  ],
  'db'=> [
      'driver'=>'mysql',
      'host'=>'localhost',
      'database'=>'site',
      'username'=>'root',
      'password'=>'',
      'collation'=>'utf_unicode_ci',
      'prefix'=>''
  ]
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){
    return $capsule;
};
$container['view'] = function ($container){
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    return $view;
};
$container['Validator'] = function ($container){
    return new App\Validation\Validator($container);
};
$container['HomeController'] = function($container){
    return new \App\controllers\HomeController($container);
};
$container['AuthController'] = function($container){
    return new \App\controllers\Auth\AuthController($container);
};

require __DIR__.'/../app/routes.php';
