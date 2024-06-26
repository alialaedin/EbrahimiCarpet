<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranianBankName implements ValidationRule
{
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		$validBankNames = [
			'بانک ملّی ایران',
			'بانک اقتصاد نوین',
			'بانک قرض‌الحسنه مهر ایران',
			'بانک سپه',
			'بانک پارسیان',
			'بانک قرض‌الحسنه رسالت',
			'بانک صنعت و معدن',
			'بانک کارآفرین',
			'بانک کشاورزی',
			'بانک سامان',
			'بانک مسکن',
			'بانک سینا',
			'بانک توسعه صادرات ایران',
			'بانک خاور میانه',
			'بانک توسعه تعاون',
			'بانک شهر',
			'پست بانک ایران',
			'بانک دی',
			'بانک صادرات',
			'بانک ملت',
			'بانک تجارت',
			'بانک رفاه',
			'بانک حکمت ایرانیان',
			'بانک گردشگری',
			'بانک ایران زمین',
			'بانک قوامین',
			'بانک انصار',
			'بانک سرمایه',
			'بانک پاسارگاد',
			'بانک مشترک ایران-ونزوئلا',
		];

		if (!in_array($value, $validBankNames)) {
			$fail('نام بانک وارد شده معتبر نیست.');
		}
	}
}
