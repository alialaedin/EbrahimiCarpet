<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'mobile',
		'address',
		'status'
	];
}
