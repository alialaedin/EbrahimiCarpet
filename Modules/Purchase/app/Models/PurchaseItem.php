<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Product\Models\Product;

class PurchaseItem extends Model
{
	use HasFactory;

	protected $fillable = [
		'purchase_id',
		'product_id',
		'quantity',
		'price',
		'discount',
	];

	protected static function booted(): void
	{
		static::deleting(function (PurchaseItem $purchaseItem) {
			if ($purchaseItem->purchase()->payments()->exists()) {
				throw new ModelCannotBeDeletedException('برای این خرید پرداختی ثبت شده و آیتم های آن قابل حذف نمی باشد.');
			} elseif ($purchaseItem->product->store->balance < $purchaseItem->quantit) {
				throw new ModelCannotBeDeletedException('موجودی انبار کمتر از تعداد محصول این آیتم است و قابل حذف نمی باشد.');
			}

			$store = $purchaseItem->product->store;
			$store->balance -= $purchaseItem->quantity;
			$store->save();
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
}
