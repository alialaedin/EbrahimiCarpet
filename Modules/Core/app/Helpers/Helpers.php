<?php

namespace Modules\Core\Helpers;

use Exception;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Modules\Setting\App\Models\Setting;

class Helpers
{
	public static function randomString(): string
	{
		return bcrypt(md5(md5(time() . time())));
	}

	public static function randomNumbersCode(int $digits = 4): int
	{
		return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
	}

	public static function convertFaNumbersToEn($string): array|string
	{
		$persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
		$arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

		$num = range(0, 9);
		$convertedPersianNums = str_replace($persian, $num, $string);
		$englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

		return $englishNumbersOnly;
	}

	public static function setEventNameForLog($eventName): string
	{
		if ($eventName == 'updated') {
			return 'بروزرسانی';
		}
		if ($eventName == 'deleted') {
			return 'حذف';
		}
		if ($eventName == 'created') {
			return 'ایجاد';
		}
		return $eventName;
	}

	public static function removeComma(string $value): string
	{
		return str_replace(',', '', $value);
	}

	public static function toGregorian(string $jDate): ?string
	{
		$output = null;
		$pattern = '#^(\\d{4})/(0?[1-9]|1[012])/(0?[1-9]|[12][0-9]|3[01])$#';

		if (preg_match($pattern, $jDate)) {
			$jDateArray = explode('/', $jDate);
			$dateArray = Verta::getGregorian(
				$jDateArray[0],
				$jDateArray[1],
				$jDateArray[2]
			);
			$output = implode('/', $dateArray);
		}

		return $output;
	}

	public static function getExceptionMessage(Exception $exception): string
	{
		return $exception->getMessage() . ' in file ' . $exception->getFile() . ' on line ' . $exception->getLine();
	}

	public static function makeValidationException($message, $key = 'unknown'): HttpResponseException
	{
		return new HttpResponseException(response()->error($message, 422));
	}

	public static function makeWebValidationException($message, $key = 'unknown', $errorBag = 'default'): ValidationException
	{
		return ValidationException::withMessages([
			$key => [$message]
		])
			->errorBag($errorBag);
	}


	public static function setting(string $name, string $default = '')
	{
		$settings = Cache::rememberForever('all_settings', function () {
			return Setting::query()->pluck('value', 'name');
		});

		return $settings[$name] ?? $default;
	}

	public static function checkCart(Collection $carts): array
	{
		$notifications = [];

		foreach ($carts as $cart) {

			$productBalance = $cart->product->store->balance;
			$cartQuantity = $cart->quantity;

			if ($cartQuantity > $productBalance) {
				$cart->delete();
				$notifications[] = "محصول با عنوان {$cart->product->title} ناموجود شد!";
			}

			if ($cart->exists()) {

				$currentPrice = $cart->price;
				$newPrice = $cart->product->totalPriceWithDiscount();

				if ($currentPrice != $newPrice) {
					$cart->update(['price' => $newPrice]);
					$notifications[] = "قیمت محصول {$cart->product->title} به {$newPrice} ریال تغییر پیدا کرده!";
				}
			}
		}

		return $notifications;
	}
}
