<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Expense extends BaseModel
{
  use LogsActivity, HasCache;

  protected $fillable = [
    'headline_id',
    'title',
    'amount',
    'payment_date',
    'description'
  ];

  // CONSTANTS
  protected const CACHE_KEYS = [
    'all_expenses'
  ];

  // Log Activity
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "هزینه با شناسه عددی $this->id را" . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::saved(fn (Expense $expense) => $expense->forgetAll(static::CACHE_KEYS));

    static::created(fn () => toastr()->success('هزینه جدید با موفقیت ثبت شد.'));

    static::updated(fn () => toastr()->success('هزینه با موفقیت بروزرسانی شد.'));

    static::deleted(function (Expense $expense) {
      $expense->forgetAll(static::CACHE_KEYS);
      toastr()->success('هزینه با موفقیت حذف شد.');
    });
  }

  public function scopeFilters($query)
  {
    return $query 
      ->when(request('headline_id'), fn($q) => $q->where('headline_id', request('headline_id')))
      ->when(request('title'), fn($q) => $q->where('title', 'like', "%". request('title') ."%"))
      ->when(request('from_payment_date'), fn($q) => $q->whereDate('payment_date', '>=', request('from_payment_date')))
      ->when(request('from_payment_date'), fn($q) => $q->whereDate('payment_date', '<=', request('from_payment_date')));
  }  

  // Relations
  public function headline(): BelongsTo
  {
    return $this->belongsTo(Headline::class);
  }
}
