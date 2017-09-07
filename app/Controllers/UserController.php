<?php

namespace App\Controllers;
use App\Models\User;

class UserController extends Controller
{
  public function getChangePassword($request, $response)
  {
      return $this->view->render($response, 'update/change_password.twig');
  }
  public function postChangePassword($request, $response)
  {
    $old = $request->getParam('old');
    $new = $request->getParam('new');
    $confirm = $request->getParam('confirm');

    $v = $this->Validator->validate([
      'old'  => [$old,'required|min(6)|checkPassword'],
      'new' => [$new,'required|min(6)'],
      'confirm' => [$confirm,'required|matches(new)'],
    ]);

    if ($v->passes()){
      $user = $this->container->auth->user();
      $user->update([
      'password' => password_hash($new, PASSWORD_DEFAULT),
      ]);

      $this->Mail->send('email/update/changed_password.twig',['user' => $user],function($message) use ($user){
        $message->to($user->email);
        $message->subject('Acount Update');
      });

      $this->flash->addMessage('info','Password changed');
      return $response->withRedirect($this->router->pathFor('home'));
    }else {
      return $this->view->render($response, 'update/change_password.twig',['errors'  => $v->errors()]);
    }
  }
  public function getRecoverPassword($request, $response)
  {
    return $this->view->render($response, 'update/recover_password.twig');
  }
  public function postRecoverPassword($request, $response)
  {
    $email = $request->getParam('email');
    $v = $this->container->Validator->validate(['email'=>[$email,'required|min(10)|email'] ]);
    if ($v->passes()){
      $user = User::where('email', $email)->first();
      if(!$user){
        $this->flash->addMessage('error','Email does not match');
        return $response->withRedirect($this->router->pathFor('recover.password'));
      }else{
        $recoverHash = $this->container->randomlib->generateString(128);
        $user->update([
          'recover_hash' => $this->container->hash->hash($recoverHash)
        ]);

        $this->Mail->send('email/auth/recover.twig',['user' => $user, 'recoverHash' => $recoverHash, 'baseUrl' => $this->container['settings']['baseUrl']],function($message) use ($user){
          $message->to($user->email);
          $message->subject('Recover you`re password');
        });
        $this->flash->addMessage('info','We had emailed you check youre email.');
      }
      return $response->withRedirect($this->router->pathFor('home'));
    }else {
      return $this->view->render($response, 'update/recover_password.twig',['errors'  => $v->errors()]);
    }
  }
  public function getResetPassword($request, $response)
  {
    $email = $request->getParam('email');
    $recoverHash = $request->getParam('recoverHash');
    $hashedrecover = $this->container->hash->hash($recoverHash);

    $user = User::where('email', $email)->first();
    if(!$user && !$user->recover_hash){
      return $response->withRedirect($this->router->pathFor('home'));
    }
    if(!$this->container->hash->hashCheck($user->recover_hash, $hashedrecover)){
      return $response->withRedirect($this->router->pathFor('home'));
    }
    return $this->view->render($response, 'update/reset_password.twig',[
      'email' => $user->email,
      'hash'  => $recoverHash
    ]);
  }
  public function postResetPassword($request, $response)
  {
    $email = $request->getParam('email');
    $recoverHash = $request->getParam('hash');
    $hashedrecover = $this->container->hash->hash($recoverHash);

    $user = User::where('email', $email)->first();
    if(!$user && !$user->recover_hash){
      return $response->withRedirect($this->router->pathFor('home'));
    }
    if(!$this->container->hash->hashCheck($user->recover_hash, $hashedrecover)){
      return $response->withRedirect($this->router->pathFor('home'));
    }
    $new = $request->getParam('new');
    $confirm = $request->getParam('confirm');

    $v = $this->Validator->validate([
      'new' => [$new,'required|min(6)'],
      'confirm' => [$confirm,'required|matches(new)'],
    ]);
    if($v->passes()){

      $user->update([
      'password'     => password_hash($new, PASSWORD_DEFAULT),
      'recover_hash' => null
      ]);
      $this->flash->addMessage('info','Password has been sucsa');
      return $response->withRedirect($this->router->pathFor('home'));
    }

  }


}
