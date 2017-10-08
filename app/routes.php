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

  $app->get('/ajax/people/see', 'AjaxController:getPeopleSee')->setName('ajax-people-see');
})->add(new GuestMiddleware($container));

$app->group('',function() use ($app){

  $app->get('/ajax/payments', 'AjaxController:getPayments')->setName('ajax-payments');
  $app->get('/ajax/people', 'AjaxController:getPeople')->setName('ajax-people-addMoney');
  $app->get('/ajax/people/sub', 'AjaxController:getPeopleSub')->setName('ajax-people-subMoney');
  $app->get('/ajax/people/delete', 'AjaxController:getPeopleDelete')->setName('ajax-people-delete');
  $app->get('/ajax/people/add', 'AjaxController:getPeopleAdd')->setName('ajax-people-add');
  $app->get('/ajax/people/search', 'AjaxController:getPeopleSearch')->setName('ajax-people-search');

  $app->get('/payments', 'PaymentsController:getPayments')->setName('payments');
  $app->post('/payments', 'PaymentsController:postPayments');

  $app->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

  $app->get('/update/profile', 'UserController:getUpdate')->setName('update');
  $app->post('/update/profile', 'UserController:postUpdate');

})->add(new AuthMiddleware($container));
