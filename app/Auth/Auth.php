<?php
namespace App\Auth;
use App\Models\User;
use App\Models\Payments;
use App\Models\People;
use Carbon\Carbon;

class Auth
{
	protected $container;

	public function __construct($container){
		$this->container = $container;
	}
	public function __get($property){
		if($this->container->{$property}){
			return $this->container->{$property};
		}
	}
	public function user(){
		if(isset($_SESSION[$this->config['auth.session'] ])){
		  return User::find($_SESSION[$this->config['auth.session']]);
	  }
	}
	public function check(){
		return isset($_SESSION[$this->config['auth.session']]);
	}
	public function attempt($username, $password, $remember)
	{
		$user = User::where('username', $username)->where('active', true)->first();
		if(!$user){
			return false;
		}
		if(password_verify($password, $user->password)){
			if($remember === 'on'){
				$rememberIdentifier = $this->randomlib->generateString(128);
				$rememberToken = $this->randomlib->generateString(128);
				$user->updateRememberCredentials($rememberIdentifier, $this->hash->hash($rememberToken));
				setcookie($this->config['auth.remember'], "{$rememberIdentifier}___{$rememberToken}", Carbon::parse('+1 week ')->timestamp,'/');
      }
			$_SESSION[$this->config['auth.session']] = $user->id;
			return true;
		}
	}
	public function logout()
	{
		if(isset($_COOKIE[$this->config['auth.remember']])){
      $this->user()->removeRememberCredentials();
			setcookie($this->config['auth.remember'], null, 1, "/", null);
			unset($_SESSION[$this->config['auth.session']]);
		}else{
			unset($_SESSION[$this->config['auth.session']]);
		}
	}
	public function expense($user = null )
	{
		if($user){
			$payments = Payments::where('user_id',$user)->get();
		}else{
		  $payments = Payments::where('user_id',$_SESSION[$this->config['auth.session']])->get();
    }
		$expense = 0;
  	foreach ($payments as $payment) {
  	  $expense += $payment->charge;
  	}
		return $expense;
	}
	public function peopleCash()
	{
    $people = People::where('user_id', $_SESSION[$this->config['auth.session']])->get();
		$cash = 0;
		foreach ($people as $person) {
			$cash += $person->balance;
		}
		return $cash;
	}
	public function paymentsCheck()
	{
		$payments = Payments::where('user_id', $this->auth->user()->id)->orderBy('id')->get();
    $cash = $this->peopleCash();
		foreach ($payments as $payment) {
			if (($cash - $payment->charge * $this->user()->people) >= 0) {
				$payment->update(['done' => true]);
				$cash = $cash - $payment->charge * $this->user()->people;
			}
		}
	}
}
