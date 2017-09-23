<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class People Extends Model
{
	protected $table = "people";

	protected $fillable = [
		'name',
    'balance'
	];
}
