<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Models\Admin;
use Modules\Core\Models\BaseModel;
use Modules\Product\Models\Price;
use Modules\Product\Models\Product;
use Modules\Purchase\Models\PurchaseItem;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Store extends BaseModel
{
  use LogsActivity;

	protected $fillable = ['product_id', 'balance', 'price_id', 'priority'];

  public function getActivitylogOptions(): LogOptions
	{
    $admin = auth()->user() ?? Admin::where('mobile', '09368917169')->first();

		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) use ($admin) {

				$eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();
				$messageBase = "ادمین با شناسه {$admin->id}, {$admin->name}, در تاریخ {$eventDate} ساعت {$eventTime}";
				$productTitle = $this->product->title;
				$productSubTitle = $this->product->sub_title;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک محصول جدید با عنوان {$productTitle} در ابعاد {$productSubTitle} را در انبار ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} محصول با عنوان {$productTitle} در ابعاد {$productSubTitle} را در انبار ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} محصول با عنوان {$productTitle} در ابعاد {$productSubTitle} را در انبار حذف کرد.";
            break;
        }

				return $message;
			});
	}

	// Relations
  public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

  public function price(): BelongsTo
  {
    return $this->belongsTo(Price::class);
  }

  public function item(): BelongsTo
  {
    return $this->belongsTo(PurchaseItem::class, 'purchase_item_id');
  }

	public function transactions(): HasMany
	{
		return $this->hasMany(StoreTransaction::class);
	}

  public static function scopeFindByPriceId(Builder $query, int $priceId): Builder|null|Store
  {
    return $query->where('price_id', $priceId)->first();
  }
}
