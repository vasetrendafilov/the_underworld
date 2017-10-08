<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User Extends Model
{
	protected $table = "users";

	protected $fillable = [
		'username',
		'name',
		'email',
		'password',
		'school',
		'people',
		'active',
    'active_hash',
    'recover_hash',
    'remember_identifier',
    'remember_token'
	];
	public function addPerson()
	{
		return $this->hasMany('App\Models\People');
	}
	public function addPayment()
	{
		return $this->hasMany('App\Models\Payments');
	}
	public function updateRememberCredentials($identifier, $token)
	{
		$this->update([
			'remember_identifier' => $identifier,
			'remember_token'      => $token
		]);
	}
	public function removeRememberCredentials(){
		$this->updateRememberCredentials(null, null);
	}
}
