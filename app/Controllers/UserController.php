<?php

namespace App\Controllers;
use App\Models\User;

class UserController extends Controller
{
  public function getChangePassword($request, $response){
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
    $v = $this->Validator->validate(['email'=>[$email,'required|min(10)|email'] ]);
    if ($v->passes()){
      $user = User::where('email', $email)->first();
      if(!$user){
        $this->flash->addMessage('error','Email does not match');
        return $response->withRedirect($this->router->pathFor('password.recover'));
      }else{
        $recover_hash = $this->randomlib->generateString(128);
        $user->update([
          'recover_hash' => $this->hash->hash($recover_hash)
        ]);
        $this->Mail->send('email/auth/recover.twig',['user' => $user, 'recover_hash' => $recover_hash],function($message) use ($user){
          $message->to($user->email);
          $message->subject('Recover you`re password');
        });
        $this->flash->addMessage('info','We had emailed you check youre email.');
      }
      return $response->withRedirect($this->router->pathFor('home'));
    }else {
      return $this->view->render($response, 'update/recover_password.twig',[
        'errors'  => $v->errors()
      ]);
    }
  }
  public function getResetPassword($request, $response)
  {
    $array = $this->zastita($request, $response);
    $user = $array[0];
    $recover_hash = $array[1];
    return $this->view->render($response, 'update/reset_password.twig',[
      'email' => $user->email,
      'recover_hash'  => $recover_hash
    ]);
  }
  public function postResetPassword($request, $response)
  {
    $user = $this->zastita($request, $response)[0];
    $password = $request->getParam('password');
    $password_confirm = $request->getParam('password_confirm');
    $v = $this->Validator->validate([
      'password' => [$password,'required|min(6)'],
      'password_confirm' => [$password_confirm,'required|matches(password)'],
    ]);
    if($v->passes())
    {
      $user->update([
      'password'     => password_hash($password, PASSWORD_DEFAULT),
      'recover_hash' => null
      ]);
      $this->flash->addMessage('info','Password has been sucsa');
      return $response->withRedirect($this->router->pathFor('home'));
    }
  }
  public function zastita($request, $response)
  {
    $email = $request->getParam('email');
    $recover_hash = $request->getParam('recover_hash');
    $hashed_recover = $this->hash->hash($recover_hash);
    $user = User::where('email', $email)->first();

    if(!$user && !$user->recover_hash){
      return $response->withRedirect($this->router->pathFor('home'));
    }
    if(!$this->hash->hashCheck($user->recover_hash, $hashed_recover)){
      return $response->withRedirect($this->router->pathFor('home'));
    }
    return [$user,$recover_hash];
  }
}
