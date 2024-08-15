<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Models\BaseModel;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Sale\Models\SaleItem;
use Modules\Store\Models\Store;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends BaseModel
{
	use HasFactory, LogsActivity;

	protected $fillable = [
		'category_id',
		'title',
		'print_title',
		'sub_title',
		'parent_id',
		'description',
		'price',
		'status',
		'discount',
		'image'
	];

	protected static function booted(): void
	{
		static::deleting(function (Product $product) {
			if ($product->stores->sum('balance') > 0) {
				throw new ModelCannotBeDeletedException('از این محصول در انبار موجود است و قابل حذف نمی باشد.');
			} elseif ($product->purchaseItems->isNotEmpty()) {
				throw new ModelCannotBeDeletedException('ازین محصول خریدی ثبت شده است و قابل حذف نمی باشد.');
			}elseif ($product->saleItems->isNotEmpty()) {
				throw new ModelCannotBeDeletedException('ازین محصول فروشی ثبت شده است و قابل حذف نمی باشد.');
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

  public function isDeletable(): bool
  {
	$hasBalance = ($this->stores->sum('balance') > 0) || !is_null($this->stores->sum('balance'));
	$hasPurchaseItem = $this->purchaseItems->isNotEmpty();
	$hasSaleItem = $this->saleItems->isNotEmpty();
	$hasChildren = $this->children->isNotEmpty();

    return !$hasBalance && !$hasPurchaseItem && !$hasSaleItem && !$hasChildren;
  }

	public function getTotalDimensionsStoreBalance(): int
	{
		$children = $this->children;
		$totsalBalance = 0;

		foreach ($children as $child) {
			$balance = $child->stores->sum('balance');
			$totsalBalance += $balance;
		}	

		return $totsalBalance;
	}

	public function loadStoreBalance()
	{
		return $this->stores->sum('balance');
	}

	public function loadDeletableMessages(): array
	{
		return [
			'اگر موجودی انبار آن حداقل یکی یا بیشتر باشد!',
			'اگر از این محصول در فاکتور فروشی ثبت شده باشد!',
			'اگر از این محصول فاکتور خریدی ثبت شده باشد!',
			'اگر محصول اصلی دارای ابعاد باشد!'
		];
	}

  // Relations
  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  public function stores(): HasMany
  {
    return $this->hasMany(Store::class);
  }

  public function purchaseItems(): HasMany
  {
    return $this->hasMany(PurchaseItem::class);
  }

  public function saleItems(): HasMany
  {
    return $this->hasMany(SaleItem::class);
  }

  public function prices(): HasMany
  {
    return $this->hasMany(Price::class);
  }

  public function children(): HasMany
  {
    return $this->hasMany(Product::class, 'parent_id');
  }

  public function parent(): BelongsTo
  {
    return $this->belongsTo(Product::class, 'parent_id');
  }
}
