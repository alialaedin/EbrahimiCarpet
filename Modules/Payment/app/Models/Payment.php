<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Purchase\Models\Purchase;

class Payment extends Model
{
	use HasFactory;

	private const TYPES = [
		'cash' => 'نقد',
		'installment' => 'قسط',
		'cheque' => 'چک',
	];

	protected $fillable = [
		'purchase_id',
		'amount',
		'type',
		'image',
		'payment_date',
		'due_date',
		'description',
		'status'
	];

	// Relations
	public function purchase(): BelongsTo
  {
    return $this->belongsTo(Purchase::class);
  }

	// Functions 
	public function getType()
	{
		return static::TYPES[$this->attributes['type']];
	}
}
