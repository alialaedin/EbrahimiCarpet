<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Account extends BaseModel
{
  use LogsActivity;

  protected $fillable = [
    'supplier_id',
    'bank_name',
    'account_number',
    'card_number'
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "حساب بانکی با شناسه عددی $this->id به نام {$this->supplier->name} را " . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::created(fn() => toastr()->success('حساب جدید با موفقیت ساخته شد'));
    static::updated(fn() => toastr()->success('حساب با موفقیت بروزرسانی شد'));
    static::deleted(fn() => toastr()->success('حساب با موفقیت حذف شد'));
  }

  public function supplier(): BelongsTo
  {
    return $this->belongsTo(Supplier::class);
  }


}
