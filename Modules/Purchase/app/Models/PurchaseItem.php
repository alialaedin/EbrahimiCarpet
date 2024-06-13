<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Product\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseItem extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = [
		'purchase_id',
		'product_id',
		'quantity',
		'price',
		'discount',
	];

	public function getActivitylogOptions(): LogOptions
  {
    $admin = auth()->user() ?? Admin::where('mobile', '09368917169')->first();

    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) use ($admin) {

        $eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();
        $messageBase = "ادمین با شناسه {$admin->id}, {$admin->name}, در تاریخ {$eventDate} ساعت {$eventTime}";

        switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک آیتم جدید برای  خرید با شناسه {$this->purchase_id} ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} آیتم با شناسه {$this->id} که متعلق به خرید شماره {$this->purchase_id} است را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} آیتم با شناسه {$this->id} که متعلق به خرید شماره {$this->purchase_id} بود را حذف کرد.";
            break;
        }

        return $message;
      });
  }

	protected static function booted(): void
	{
		static::deleting(function (PurchaseItem $purchaseItem) {
			if ($purchaseItem->purchase()->payments()->exists()) {
				throw new ModelCannotBeDeletedException('برای این خرید پرداختی ثبت شده و آیتم های آن قابل حذف نمی باشد.');
			} elseif ($purchaseItem->product->store->balance < $purchaseItem->quantity) {
				throw new ModelCannotBeDeletedException('موجودی انبار کمتر از تعداد محصول این آیتم است و قابل حذف نمی باشد.');
			}

			$this->decrementProductStore();
		});
	}

	// Relations
	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
	public function purchase(): BelongsTo
	{
		return $this->belongsTo(Purchase::class);
	}

	// Functions
	public function getPriceWithDiscount(): int
	{
		$price = $this->attributes['price'];
		$discount = $this->attributes['discount'];

		return is_null($discount) ? $price : $price - $discount;
	}

	public function getTotalItemPrice()
	{
		return $this->getPriceWithDiscount() * $this->attributes['quantity'];
	}

  private function decrementProductStore(): void
  {
    $store = $this->product->store;
    $purchase = $this->purchase;

    $store->decrement('balance', $this->attributes['quantity']);
    $purchase->transactions()->create([
      'store_id' => $store->id,
      'type' => 'decrement',
      'quantity' => $this->attributes['quantity']
    ]);
  }

}
