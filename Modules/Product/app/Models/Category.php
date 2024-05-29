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

	// Functions
	public function getParentTitle()
	{
		return $this->parent ? $this->parent->title : '-';
	}

	public function getUnitType()
	{
		return $this->attributes['unit_type'] == 'meter' ? 'متر' : 'عدد';
	}
}
