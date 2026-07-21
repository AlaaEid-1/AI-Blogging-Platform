<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Restricted implements ValidationRule
{
    public function __construct(protected array $words = []) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $words = explode(' ', $value);
        foreach ($words as $word) {
            if (in_array(trim($word, ' .!?,-()'), $this->words)) {
                $fail("The {$attribute} contains a restricted word: '{$word}'");
            }
        }
    }
}
