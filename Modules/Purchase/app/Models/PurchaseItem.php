<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Models\Price;
use Modules\Product\Models\Product;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;
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
		static::deleting(function (PurchaseItem $item) {
			if ($item->product->store_balance < $item->quantity) {
				throw new ModelCannotBeDeletedException('موجودی انبار کمتر از تعداد محصول این آیتم است و قابل حذف نمی باشد.');
			}
		});
		
		static::deleted(function (PurchaseItem $item) {
			StoreService::decrementStoreBalance($item->product, $item->attributes['quantity']);
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

	public function store(): HasOne
	{
		return $this->hasOne(Store::class);
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

	protected static function updateQuantityAndPrice(self $purchaseItem, Request $request) 
	{
		if ($request->quantity)
			self::updateQuantity($purchaseItem, $request);
		if ($request->price)
			self::updatePrice($purchaseItem, $request->price);
		
		if ($request->discount) {
			$purchaseItem->discount = $request->discount;
			$purchaseItem->save();		
		}
	}

	private static function updateQuantity($purchaseItem, $request)
	{
		$oldQuantity = $purchaseItem->quantity;
		$newQuantity = $request->quantity;
		
		$product = $purchaseItem->product;
		
		$purchasedPrice = $request->price ?? $purchaseItem->price;
		$diffQuantity = abs($newQuantity - $oldQuantity);

		if ($newQuantity > $oldQuantity) {
			StoreService::addProductToStore($product, $purchasedPrice, $diffQuantity);
		}else {
			StoreService::decrementStoreBalance($product, $diffQuantity);
		}

		$purchaseItem->quantity = $newQuantity;
		$purchaseItem->save();
	} 

	private static function updatePrice($purchaseItem, $newPrice)
	{
		Price::query()
			->where('product_id', $purchaseItem->product_id)
			->where('buy_price', $purchaseItem->price)
			->orderByDesc('id')
			->first()
			->update([
				'buy_price' => $newPrice
			]);

		$purchaseItem->price = $newPrice;
		$purchaseItem->save();
	}

}
