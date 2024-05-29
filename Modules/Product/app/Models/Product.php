<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Store\Models\Store;
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

	protected static function booted(): void
	{
		static::deleting(function (Category $category) {
			if ($category->store()->exists()) {
				throw new ModelCannotBeDeletedException('از این محصول در انبار موجود است و قابل حذف نمی باشد.');
			} elseif ($category->purchaseItems()->exists()) {
				throw new ModelCannotBeDeletedException('ازین محصول خریدی ثبت شده است و قابل حذف نمی باشد.');
			}
		});
	}

	public function getActivitylogOptions(): LogOptions
	{
    $admin = auth()->user() ?? Admin::where('mobile', '09368917169')->first();

		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) use ($admin) {
				
				$eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();
				$messageBase = "ادمین با شناسه {$admin->id}, {$admin->name}, در تاریخ {$eventDate} ساعت {$eventTime}";
				$productTitle = $this->title;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک محصول جدید با عنوان {$productTitle} را ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} محصول با عنوان {$productTitle} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} محصول با عنوان {$productTitle} را حذف کرد.";
            break;
        }

				return $message;
			});
	}

	// Relations
	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	public function store(): HasOne
	{
		return $this->hasOne(Store::class);
	}
	
	public function purchaseItems(): HasMany
	{
		return $this->hasMany(PurchaseItem::class);
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
