<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermissions Extends Model{

	protected $table = "users_permissions";

	protected $fillable = [
		'is_admin',
	];
	public static $defaults=[
		'is_admin' => false
	];

}
