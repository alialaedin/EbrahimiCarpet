<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranianShebaNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^IR[0-9]{24}$/', $value)) {
            $fail('شماره شبا وارد شده نامعتبر است!');
        }
    }
}
