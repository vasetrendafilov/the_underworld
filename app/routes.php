<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:getPeople')->setName('home');
$app->post('/', 'HomeController:postPeople');

$app->group('',function() use ($app){

  $app->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
  $app->post('/auth/signup', 'AuthController:postSignUp');

  $app->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
  $app->post('/auth/signin', 'AuthController:postSignIn');

  $app->get('/activate', 'AuthController:getActivate')->setName('activate');

  $app->get('/recover-password', 'UserController:getRecoverPassword')->setName('password.recover');
  $app->post('/recover-password', 'UserController:postRecoverPassword');

  $app->get('/reset-password', 'UserController:getResetPassword')->setName('password.reset');
  $app->post('/reset-password', 'UserController:postResetPassword');

})->add(new GuestMiddleware($container));

$app->group('',function() use ($app){

  $app->get('/ajax/payments', 'AjaxController:getPayments')->setName('ajax-payments');

  $app->get('/payments', 'PaymentsController:getPayments')->setName('payments');
  $app->post('/payments', 'PaymentsController:postPayments');

  $app->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

  $app->get('/changePassword', 'UserController:getChangePassword')->setName('password.change');
  $app->post('/changePassword', 'UserController:postChangePassword');

})->add(new AuthMiddleware($container));
