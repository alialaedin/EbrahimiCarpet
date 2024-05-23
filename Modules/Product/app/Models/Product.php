<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = [
		'category_id',
		'title',
		'description',
		'price',
		'status',
		'discount',
		'image'
	];

	public function getActivitylogOptions(): LogOptions
	{
		$events = [
			'created' => 'ایجاد کرد',
			'updated' => 'ویرایش کرد',
			'deleted' => 'حذف کرد',
		];

		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) use ($events) {
				$model = $this;
				$admin = auth()->user() ?? Admin::where('mobile', '09368917169')->first();
				$createdDate = verta($model->created_at)->format('Y/m/d');
				$message = "ادمین با شناسه {$admin->id} ({$admin->name}) در تاریخ {$createdDate} ";

				if (array_key_exists($eventName, $events)) {
					$action = $events[$eventName];
					$message .= "محصول با شناسه {$model->id} ({$model->title}) را {$action}.";
				}

				return $message;
			});
	}

	// Relations
	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	// Functions
	public function getDiscount()
	{
		$discount = $this->attributes['discount'];

		return ($discount == 0 || is_null($discount)) ? 0 : $discount;
	}

	public function getTotalPriceWithDiscount(): int
	{
		$discount = $this->attributes['discount'];
		$price = $this->attributes['price'];

		if (!is_null($discount)) {
			$price -= $discount;
		}

		return $price;
	}
}
