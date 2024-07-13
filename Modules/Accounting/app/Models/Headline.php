<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Accounting\Enums\HeadlineType;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Headline extends BaseModel
{
  use LogsActivity, HasCache;

  public const TYPE_REVENUE = 'revenue';
  public const TYPE_EXPENSE = 'expense';

  protected $fillable = [
    'title',
    'type',
    'status',
  ];

  // CONSTANTS
  protected const CACHE_KEYS = [
    'all_headlines'
  ];

  protected const BADGE_TYPE = [
    'revenue' => 'info',
    'expense' => 'warning',
    '1' => 'success',
    '0' => 'danger'
  ];

  // Log Activity
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "سرفصل با شناسه عددی $this->id با عنوان $this->name را" . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::saved(fn(Headline $headline) => $headline->forgetAll(static::CACHE_KEYS));

    static::created(fn() => toastr()->success('سرفصل جدید با موفقیت ساخته شد.'));

    static::updated(fn() => toastr()->success('سرفصل با موفقیت بروزرسانی شد.'));

    static::deleting(function (Headline $headline) {
      if (!$headline->isDeletable()) {
        throw new ModelCannotBeDeletedException('ازین سرفصل هزینه ای ثبت شده و قابل حذف نمی باشد!');
      }
    });

    static::deleted(function (Headline $headline) {
      $headline->forgetAll(static::CACHE_KEYS);
      toastr()->success('سرفصل با موفقیت حذف شد.');
    });
  }

  // Scope Queries
  public static function scopeGetHeadlinesByType(Builder $query, HeadlineType $type): \Illuminate\Database\Eloquent\Collection|array
  {
    return $query->select('id', 'title', 'status', 'type')
      ->where('status', 1)
      ->where('type', '=', $type)
      ->get();
  }

  // Functions
  public function getHeadlineType(): string
  {
    return config('accounting.headline_types.' . $this->attributes['type']);
  }

  public function getTypeBadgeType(): string
  {
    return static::BADGE_TYPE[$this->attributes['type']];
  }

  public function getHeadlineStatus(): string
  {
    return $this->attributes['status'] ? 'فعال' : 'غیر فعال';
  }

  public function getStatusBadgeType(): string
  {
    return static::BADGE_TYPE[$this->attributes['status']];
  }

  public function isDeletable(): bool
  {
    return $this->expenses->isEmpty() && $this->revenues->isEmpty();
  }

  // Relations
  public function expenses(): HasMany
  {
    return $this->hasMany(Expense::class);
  }

  public function revenues(): HasMany
  {
    return $this->hasMany(Revenue::class);
  }
}
