<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile Extends Model{

	protected $table = "users_profile";

	protected $fillable = [
		'grad',
    'pol',
    'nacionalnost',
    'rodenden'
	];
}
