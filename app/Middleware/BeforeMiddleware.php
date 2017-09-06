<?php

namespace App\Middleware;
use App\Models\User;

class BeforeMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		$this->checkRememberMe();

		$response = $next($request, $response);
		return $response;
	}
	protected function checkRememberMe()
	{
    $user = $this->container->auth->user();
		if(isset($_COOKIE['user_r']) && !$user)
		{
			$data = $_COOKIE['user_r'];
			$credentials = explode('___', $data);

			if(empty(trim($data)) || count($credentials) !== 2) {
			  setcookie('user_r', null, 1, "/", null);
			  return $response->withRedirect($this->container->router->pathFor('auth.signin'));
		  }else{
				$identifier = $credentials[0];
				$token = $this->container->hash->hash($credentials[1]);
        $user = User::where('remember_identifier', $identifier)->first();
				if($user){
					if($this->container->hash->hashCheck($token, $user->remember_token)){
						$_SESSION['user'] = $user->id;
						/*$this->container->view->getEnvironment()->addGlobal('auth', [
						 'check' => $this->container->auth->check(),
						 'user' => $this->container->auth->user()
					 ]);*/
					}else{
						//$user->removeRememberCredentials();
						$user->update([
							'remember_identifier' => null,
							'remember_token'      => null
						]);
						setcookie('user_r', null, 1, "/", null);
					}
			  }
			}
    }
  }
}
