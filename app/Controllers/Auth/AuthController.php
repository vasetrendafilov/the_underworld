<?php

namespace App\Controllers\Auth;
use App\Models\User;
use App\Controllers\Controller;
use Carbon\Carbon;
class AuthController extends Controller
{
  public function getActivate($request, $response)
  {
    $email = $request->getParam('email');
    $active_string = $request->getParam('active_string');
    $active_hash = $this->hash->hash($active_string);
    $user = User::where('email',$email)
     ->where('active', false)->first();
    if(!$user || !$this->hash->hashCheck($user->active_hash, $active_hash))
    {
      $this->flash->addMessage('error','We could not activate you\'re acount ');
      return $response->withRedirect($this->router->pathFor('home'));
    }else {
      $user->update([
        'active' => true,
        'active_hash' => null
      ]);
      $this->flash->addMessage('info','You\'re acount is activated you can sing in');
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

  }
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
      $name = $request->getParam('name');
      $password = $request->getParam('password');
      $password_confirm = $request->getParam('password_confirm');

      $v = $this->Validator->validate([
        'username' => [$username,'required|alnumDash|max(20)|uniqueUsername'],
        'email' => [$email,'required|email|uniqueEmail'],
        'name'  => [$name,'required|min(10)'],
        'password' => [$password,'required|min(6)'],
        'password_confirm' => [$password_confirm,'required|matches(password)'],
      ]);

      if ($v->passes()){
        $active_string = $this->randomlib->generateString(128);
        $user = User::create([
        'username'    => $username,
        'email'       => $email,
        'name'        => $name,
        'password'    => password_hash($password, PASSWORD_DEFAULT),
        'active'      => false,
        'active_hash' => $this->hash->hash($active_string)
        ]);

        $this->Mail->send('email/auth/activate.twig',['user' => $user, 'active_string' => $active_string, 'baseUrl' => $this->container['settings']['baseUrl'] ],function($message) use ($user){
          $message->to($user->email);
          $message->subject('thanke for regestering');
        });

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
    $remember = $request->getParam('remember');

    $auth = $this->auth->attempt($username, $password, $remember);
    if(!$auth){
      $this->flash->addMessage('error','Could not sign you in');
       return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

    $this->flash->addMessage('info','You are singed in.');
    return $response->withRedirect($this->router->pathFor('home'));

  }
}
