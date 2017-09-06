<?php
namespace App\Auth;
use App\Models\User;
use Carbon\Carbon;

class Auth
{
	protected $container;

	public function __construct($container)
	{
			$this->container = $container;
	}
	public function user(){
		if(isset($_SESSION['user'])){
		return User::find($_SESSION['user']);
	}
	}
	public function check(){
		return isset($_SESSION['user']);

	}
	public function attempt($username, $password, $remember){
		$user = User::where('username', $username)->where('active', true)->first();
		if(!$user){
			return false;
		}
		if(password_verify($password, $user->password)){
			if($remember === 'on'){
				$rememberIdentifier = $this->container->randomlib->generateString(128);
				$rememberToken = $this->container->randomlib->generateString(128);
				$user->update([
					'remember_identifier' => $rememberIdentifier,
					'remember_token'      => $this->container->hash->hash($rememberToken)
				]);
				setcookie('user_r', "{$rememberIdentifier}___{$rememberToken}", Carbon::parse('+1 week ')->timestamp,'/');
      }
			$_SESSION['user'] = $user->id;
			return true;
		}
		  return false;
	}
	public function logout()
	{
		if(isset($_COOKIE['user_r'])){
			//$this->user()->removeRememberCredentials();
      $user = User::where('id',$_SESSION['user'])->first();
			$user->update([
				'remember_identifier' => null,
				'remember_token'      => null
			]);
			setcookie('user_r', null, 1, "/", null);
			unset($_SESSION['user']);
		}else{
			unset($_SESSION['user']);
		}
	}
}
