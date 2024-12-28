<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
	use HasFactory, LogsActivity;

  public const UNIT_TYPE_METER = 'meter';
  public const UNIT_TYPE_NUMBER = 'number';

	protected $fillable = [
		'parent_id',
		'title',
		'unit_type',
		'status'
	];

	protected static function booted(): void
	{
		static::deleting(function (Category $category) {
			if ($category->children()->exists()) {
				throw new ModelCannotBeDeletedException('دسته بندی دارای فرزند است و قابل حذف نمی باشد.');
			} elseif ($category->products()->exists()) {
				throw new ModelCannotBeDeletedException('از این دسته بندی محصول یا محصولاتی ثبت شده است و قابل حذف نمی باشد.');
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
				$categoryTitle = $this->title;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک دسته بندی جدید با عنوان {$categoryTitle} را ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} دسته بندی با عنوان {$categoryTitle} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} دسته بندی با عنوان {$categoryTitle} را حذف کرد.";
            break;
        }

				return $message;
			});
	}

	// Functions
	public function getParentTitleAttribute(): string
	{
		return $this->parent ? $this->parent->title : '-';
	}

	public function getUnitType(): string
	{
    return config('core.category_unit_types.' . $this->attributes['unit_type']);
	}

  public function isDeletable(): bool
  {
    return $this->children->isEmpty() && $this->products->isEmpty();
  }

  public function scopeChildren($query)
  {
		return $query->whereNotNull('parent_id');
  }

	public function scopeParents($query)
  {
		return $query->whereNull('parent_id');
  }

	public function scopeWithCountProducts($query)
	{
		return $query->withCount('products');
	} 
	public function scopeWithParents($query)
	{
		return $query->with('parent', fn ($q) => $q->select(['id', 'title']));
	}

  public function scopeFilters($query)
	{
		return $query
			->when(request('title'), fn ($q) => $q->where('title', 'like', "%". request('title') ."%"))
			->when(request('parent_id'), function ($q) {
				return $q->where(function ($q) {
					if (request('parent_id') == 'none') {
						$q->whereNull('parent_id');
					} else {
						$q->where('parent_id', request('parent_id'));
					}
				});
			})
			->when(request('unit_type'), fn ($q) => $q->where('unit_type', request('unit_type')))
			->when(!is_null(request('status')), fn ($q) => $q->where('status', request('status')));
	}

	public static function getParentCategories()
	{
		return self::query()
			->select(['id', 'title', 'unit_type'])
			->parents()
			->with('children:id,title,parent_id')
			->get();
	}

  // Relations
  public function children(): HasMany
  {
    return $this->hasMany(Category::class, 'parent_id');
  }

  public function parent(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  public function products(): HasMany
  {
    return $this->hasMany(Product::class);
  }

}
