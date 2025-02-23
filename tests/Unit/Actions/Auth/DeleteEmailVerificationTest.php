<?php

declare(strict_types=1);

use App\Actions\Auth\DeleteEmailVerification;
use App\Models\EmailVerification;

describe('as job', function (): void {
    it('can delete email verification for a given email', function (): void {
        $email = fake()->email();
        $emailVerification = EmailVerification::factory()->create(['email' => $email]);

        Queue::fake();

        DeleteEmailVerification::dispatch($email);

        DeleteEmailVerification::assertPushed(1, static function (DeleteEmailVerification $job, array $args) use ($email): bool {
            $job->handle($email);

            return $args[0] === $email;
        });

        $this->assertDatabaseMissing(EmailVerification::class, [
            'id' => $emailVerification->id,
            'email' => $email,
        ]);
    });
});

describe('as action', function (): void {
    it('can delete email verification for a given email', function (): void {
        $email = fake()->email();
        $emailVerification = EmailVerification::factory()->create(['email' => $email]);

        DeleteEmailVerification::run($email);

        $this->assertDatabaseMissing(EmailVerification::class, [
            'id' => $emailVerification->id,
            'email' => $email,
        ]);
    });
});

it('has unique job id', function (): void {
    $email = fake()->email();
    $job = new DeleteEmailVerification();
    $jobUniqueId = $job->getJobUniqueId($email);

    expect($jobUniqueId)->toBe("delete-email-verification-$email");
});
