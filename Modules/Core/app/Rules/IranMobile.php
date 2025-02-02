<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranMobile implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^09(1[0-9]|9[0-4]|2[0-2]|0[1-5]|41|3[0,3,5-9])\d{7}$/', $value)) {
            $fail('شماره موبایل وارد شده نامعتبر است!');
        }
    }
}
