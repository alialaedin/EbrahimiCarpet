<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranianCardNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(6037|6104|6273|6274|6277|6278|6279|6280|6281|6282|6283|6284|6285|6286|6287|6288|6289|6362|6363|6393|6394|6395|6396|6397|8101|8102|8103|9919)\d+$/', $value)) {
            $fail('شماره کارت وارد شده نامعتبر است!');
        }
    }
}
