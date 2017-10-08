<?php

namespace App\Controllers;
use App\Models\User;

class UserController extends Controller
{
  public function getUpdate($request, $response){
    $user = $this->auth->user();
    return $this->view->render($response, 'update/update.twig',[
    'user' => $user
    ]);
  }
  public function postUpdate($request, $response)
  {
    $name = $request->getParam('name');
    $school = $request->getParam('school');
    $password = $request->getParam('password');
    $password_new = $request->getParam('password_new');
    $password_confirm = $request->getParam('password_confirm');

    $v = $this->Validator->validate([
      'password'  => [$password,'required|min(6)|checkPassword'],
      'password_new' => [$password_new,'required|min(6)'],
      'password_confirm' => [$password_confirm,'required|matches(password_new)']
    ]);
    if ($v->passes()){
      $user = $this->container->auth->user();
      $user->update([
      'password' => password_hash($password_new, PASSWORD_DEFAULT),
      ]);

      $this->Mail->send('email/update/changed_password.twig',['user' => $user],function($message) use ($user){
        $message->to($user->email);
        $message->subject('Акаунт Упдејт');
      });

      $this->flash->addMessage('info','Твојата лозинка е променета');
      return $response->withRedirect($this->router->pathFor('home'));
    }else {
      if(!empty($name)&&!empty($school)){
        $user = $this->container->auth->user();
        if($user->name != $name || $user->school != $school){
          $user->update([
          'name' => $name,
          'school' => $school
          ]);
          $this->flash->addMessage('info','Персоналните детали се сменети');
          return $response->withRedirect($this->router->pathFor('home'));
        }
      }
      return $this->view->render($response, 'update/update.twig',['errors'  => $v->errors()]);

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
        $this->flash->addMessage('error','Емаилот не се сопфаѓа');
        return $response->withRedirect($this->router->pathFor('password.recover'));
      }else{
        $recover_hash = $this->randomlib->generateString(128);
        $user->update([
          'recover_hash' => $this->hash->hash($recover_hash)
        ]);
        $this->Mail->send('email/auth/recover.twig',['user' => $user, 'recover_hash' => $recover_hash],function($message) use ($user){
          $message->to($user->email);
          $message->subject('Повраити ја лозинката');
        });
        $this->flash->addMessage('info','Ви пративме емаил деталите за враќање на лозинката се во пораката');
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
      $this->flash->addMessage('info','Лозинката е успешно променета');
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
