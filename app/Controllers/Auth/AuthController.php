<?php

namespace App\Controllers\Auth;
use App\Models\User;
use App\Controllers\Controller;

class AuthController extends Controller
{
  public function getSignOut($request, $response)
  {
    $this->auth->logout();
    $this->flash->addMessage('info','You are singed out');
    return $response->withRedirect($this->router->pathFor('home'));

  }
  public function getSignUp($request, $response)
  {
      return $this->view->render($response, 'auth/signup.twig');
  }
  public function postSignUp($request, $response)
  {
      $username = $request->getParam('username');
      $email = $request->getParam('email');
      $password = $request->getParam('password');
      $password_confirm = $request->getParam('password_confirm');

      $v = $this->Validator->validate([
        'email' => [$email,'required|email|uniqueEmail'],
        'username' => [$username,'required|alnumDash|max(20)|uniqueUsername'],
        'password' => [$password,'required|min(6)'],
        'password_confirm' => [$password_confirm,'required|matches(password)'],
      ]);

      if ($v->passes()){
        $user = User::create([
        'username' => $username,
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
        $this->flash->addMessage('info','You are singed up');
        return $response->withRedirect($this->router->pathFor('home'));
      }else {
        return $this->view->render($response, 'auth/signup.twig',[
          'errors'  => $v->errors(),
          'request' => $request->getParams()
        ]);
      }
  }
  public function getSignIn($request, $response)
  {
      return $this->view->render($response, 'auth/signin.twig');
  }
  public function postSignIn($request, $response)
  {
    $username = $request->getParam('username');
    $password = $request->getParam('password');

    $auth = $this->auth->attempt($username, $password);
    if(!$auth){
      $this->flash->addMessage('error','Could not sign uoy in');
       return $response->withRedirect($this->router->pathFor('auth.signin'));
    }
    $this->flash->addMessage('info','You are singed in.');
    return $response->withRedirect($this->router->pathFor('home'));

  }
}
