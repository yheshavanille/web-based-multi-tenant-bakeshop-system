<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PersonName implements ValidationRule
{
    /**
     * Allow: letters (incl. Unicode like ñ, é, Ñ), spaces, hyphens, apostrophes, periods.
     * Block: emojis, digits, symbols, and any other weird characters.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('The :attribute must be a valid name.');
            return;
        }

        // Trim to avoid leading/trailing whitespace false positives
        $trimmed = trim($value);

        if ($trimmed === '') {
            $fail('The :attribute is required.');
            return;
        }

        // Regex: letters (Unicode), spaces, hyphens, apostrophes, periods
        // \p{L} = any Unicode letter (covers ñ, é, Ñ, etc.)
        // \p{M} = combining marks (for accented letters in some encodings)
        if (!preg_match("/^[\p{L}\p{M}][\p{L}\p{M}\s\.\-']*$/u", $trimmed)) {
            $fail('The :attribute may only contain letters, spaces, hyphens, apostrophes, and periods. Emojis and special characters are not allowed.');
            return;
        }

        // Must contain at least one letter (blocks just "   " or "---")
        if (!preg_match("/\p{L}/u", $trimmed)) {
            $fail('The :attribute must contain at least one letter.');
            return;
        }

        // Disallow double spaces (cosmetic, and common spam pattern)
        if (preg_match('/\s{2,}/u', $trimmed)) {
            $fail('The :attribute cannot contain consecutive spaces.');
            return;
        }
    }
}
