<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Models\Admin;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
  use HasFactory, LogsActivity;

  protected $fillable = [
    'name',
    'mobile',
    'telephone',
    'address',
    'status'
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

				$customerId = $this->id;
				$customerName = $this->name;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک مشتری جدید با نام {$customerName} را ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} مشتری با شناسه عددی {$customerId} به نام {$customerName} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} مشتری با شناسه عددی {$customerId} به نام {$customerName} را حذف کرد.";
            break;
        }

				return $message;
			});
	}
}
