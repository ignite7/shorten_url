<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\EmailVerification;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsObject;

final class DeleteEmailVerification
{
    use AsJob, AsObject;

    /**
     * @param  string  $email
     * @return void
     */
    public function handle(string $email): void
    {
        EmailVerification::query()
            ->where('email', $email)
            ->delete();
    }

    /**
     * @param  string  $email
     * @return string
     */
    public function getJobUniqueId(string $email): string
    {
        return "delete-email-verification-$email";
    }
}
