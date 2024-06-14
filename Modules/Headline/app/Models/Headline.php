<?php

namespace Modules\Headline\Models;


use Modules\Core\Traits\HasCache;
use Modules\Core\Models\BaseModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Headline extends BaseModel
{
  use LogsActivity, HasCache;

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
    static::saved(fn (Headline $headline) => $headline->forgetAll(static::CACHE_KEYS));
    static::created(fn () => toastr()->success('سرفصل جدید با موفقیت ساخته شد.'));
    static::updated(fn () => toastr()->success('سرفصل با موفقیت بروزرسانی شد.'));
    static::deleted(function (Headline $headline) {
      $headline->forgetAll(static::CACHE_KEYS);
      toastr()->success('سرفصل با موفقیت حذف شد.');
    });
  }

  // Functions
  public function getHeadlineType()
  {
    return config('core.headline_types.' . $this->attributes['type']);
  }

  public function getTypeBadgeType()
  {
    return static::BADGE_TYPE[$this->attributes['type']];
  }

  public function getHeadlineStatus()
  {
    return $this->attributes['status'] ? 'فعال' : 'غیر فعال';
  }

  public function getStatusBadgeType()
  {
    return static::BADGE_TYPE[$this->attributes['status']];
  }

}
