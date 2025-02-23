<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\EmailVerification;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Translation\PotentiallyTranslatedString;

final class VerificationCodeRule implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $emailVerification = EmailVerification::query()
            ->where('email', $this->data['email'] ?? null)
            ->first();

        if (! $emailVerification
            || ! Hash::check($value, $emailVerification->verification_code)
            || $emailVerification->expires_at->isPast()) {
            $fail('The :attribute field is not valid.');
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
