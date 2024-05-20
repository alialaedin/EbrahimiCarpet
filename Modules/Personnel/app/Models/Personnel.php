<?php

namespace Modules\Personnel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Personnel extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'mobile',
		'address',
		'national_code',
		'employment_at',
		'card_number',
		'sheba_number',
		'bank_name',
		'salary',
		'telephone'
	];
}
