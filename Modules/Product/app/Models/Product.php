<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Models\BaseModel;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Sale\Models\SaleItem;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;
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

	public const INDEX_PAGE_SELECTED_COLUMNS = ['id', 'title', 'print_title', 'category_id', 'status', 'discount', 'price', 'image', 'parent_id', 'created_at'];

	protected static function booted(): void
	{
		static::deleting(function (Product $product) {
			if ($product->stores->sum('balance') > 0) {
				throw new ModelCannotBeDeletedException('از این محصول در انبار موجود است و قابل حذف نمی باشد.');
			} elseif ($product->purchaseItems->isNotEmpty()) {
				throw new ModelCannotBeDeletedException('ازین محصول خریدی ثبت شده است و قابل حذف نمی باشد.');
			} elseif ($product->saleItems->isNotEmpty()) {
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
				$productSubTitle = $this->sub_title ? "در ابعاد {$this->sub_title}" : null;

				switch ($eventName) {
					case 'created':
						$message = "{$messageBase} یک محصول جدید با عنوان {$productTitle} {$productSubTitle} را ثبت کرد.";
						break;
					case 'updated':
						$message = "{$messageBase} محصول با عنوان {$productTitle} {$productSubTitle} را ویرایش کرد.";
						break;
					case 'deleted':
						$message = "{$messageBase} محصول با عنوان {$productTitle} {$productSubTitle} را حذف کرد.";
						break;
				}

				return $message;
			});
	}

	public function getDiscountAttribute()
	{
		$discount = $this->attributes['discount'];

		return ($discount == 0 || is_null($discount)) ? 0 : $discount;
	}

	public function getFullTitleAttribute()
	{
		return $this->sub_title === null ? $this->title : $this->title . ' ' . $this->sub_title;
	}

	public function isDeletable(): bool
	{
		$hasBalance = $this->stores->sum('balance') > 0;
		$hasPurchaseItem = $this->purchaseItems->isNotEmpty();
		$hasSaleItem = $this->saleItems->isNotEmpty();
		$hasChildren = $this->children->isNotEmpty();

		return !$hasPurchaseItem and !$hasSaleItem and !$hasChildren and !$hasBalance;
	}

	public function getStoreBalanceAttribute()
	{
		return $this->stores->sum('balance');
	}

	public function getDemenisionsStoreBalanceAttribute()
	{
		return $this->children->map(fn($product) => $product->store_balance)->sum();
	}

	public function loadDeletableMessages(): array
	{
		return [
			'اگر موجودی انبار آن حداقل یکی یا بیشتر باشد!',
			'اگر از این محصول فاکتور فروشی ثبت شده باشد!',
			'اگر از این محصول فاکتور خریدی ثبت شده باشد!',
			'اگر محصول اصلی دارای ابعاد باشد!'
		];
	}

	public function updateChildren(array $columns)
	{
		if ($this->children->isNotEmpty()) {
			foreach ($this->children as $product) {
				foreach ($columns as $column) {
					$product->update([
						$column => $this->attributes[$column],
					]);
				}
			}
		}
	}

	public function scopeChildren($query)
	{
		return $query->whereNotNull('parent_id');
	}

	public function scopeParents($query)
	{
		return $query->whereNull('parent_id');
	}

	public function scopeFilters($query)
	{
		return $query
			->when(request('title'), fn($q) => $q->where('title', 'like', "%" . request('title') . "%"))
			->when(request('category_id'), fn($q) => $q->where('category_id', request('category_id')))
			->when(request('product_id'), fn($q) => $q->where('id', request('product_id')))
			->when(request('start_date'), fn($q) => $q->whereDate('created_at', '>=', request('start_date')))
			->when(request('end_date'), fn($q) => $q->whereDate('created_at', '<=', request('end_date')))
			->when(request('unit_type'), function ($query) {
				return $query->withWhereHas('category', fn($query) => $query->where('unit_type', request('unit_type')));
			})
			->when(!is_null(request('status')), fn($q) => $q->where('status', request('status')))
			->when(!is_null(request('has_discount')), function ($q) {
				return $q->where(function ($q) {
					if (request('has_discount') == 1) {
						$q->where('discount', '>', 0)->orWhereNotNull('discount');
					} else {
						$q->where('discount', '=', 0)->orWhereNull('discount');
					}
				});
			});
	}

	public function getRedirectRoute()
	{
		if (is_null($this->parent_id)) {
			return to_route('admin.products.index');
		} else {
			return redirect()->back();
		}
	}

	public static function getParentProducts(array $selectedColumns = ['*'])
	{
		return self::query()
			->select($selectedColumns)
			->parents()
			->with([
				'children' => fn($q) => $q->select(['id', 'parent_id', 'sub_title', 'price']),
				'category' => fn($q) => $q->select(['id', 'title'])
			])
			->get();
	}

	public static function updatePrice(Request $request)
	{
		try {
			DB::beginTransaction();
			foreach ($request->products as $requestProduct) {
				$product = self::findOrFail($requestProduct['id']);
				$product->price = $requestProduct['price'];
				$product->save();
				StoreService::updateSellPrice($product);
			}
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();
		}
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
