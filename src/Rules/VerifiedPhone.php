<?php

declare(strict_types=1);

namespace Gogain\LaravelPhoneVerification\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

final class VerifiedPhone implements Rule
{
    /**
     * @param mixed $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return false;
        }
        
        if (!Cache::get('verified'.$value)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'Phone not verified';
    }
}

