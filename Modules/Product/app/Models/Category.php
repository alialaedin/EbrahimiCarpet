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
			}
		});
	}

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
					$message .= "دسته بندی با شناسه {$model->id} ({$model->title}) را {$action}.";
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
