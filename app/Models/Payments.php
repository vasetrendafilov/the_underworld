<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payments Extends Model
{
	protected $table = "users_payments";

	protected $fillable = [
		'description',
    'charge',
		'done'
	];
}
