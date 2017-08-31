<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User Extends Model{

	protected $table = "users";

	protected $fillable = [
		'username',
		'name',
		'email',
		'password',
		'salt',
		'active',
    'active_hash',
    'recover_hash',
    'remember_identifier',
    'remember_token'
	];

}
?>
