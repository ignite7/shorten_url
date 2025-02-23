<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Helpers\FlashHelper;
use App\Mail\SendVerificationCode;
use App\Models\EmailVerification;
use App\Validations\EmailValidation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Mail;

final class SendVerificationCodeEmail
{
    use AsController, AsObject;

    /**
     * @param  string  $email
     * @return EmailVerification
     */
    public function handle(string $email): EmailVerification
    {
        $verificationCode = Str::random(6);

        $emailVerification = EmailVerification::query()->updateOrCreate([
            'email' => $email,
        ], [
            'verification_code' => $verificationCode,
            'expires_at' => now()->addHours(3),
        ]);

        Mail::to($email)->queue(new SendVerificationCode(
            $email,
            $verificationCode
        ));

        return $emailVerification;
    }

    /**
     * @return array<string, list<Unique|string>>
     */
    public function rules(): array
    {
        return EmailValidation::rules();
    }

    /**
     * @return array<string, string>
     */
    public function getValidationAttributes(): array
    {
        return EmailValidation::validationAttributes();
    }

    /**
     * @return string[]
     */
    public function getControllerMiddleware(): array
    {
        // Throttle: Allow up to 3 requests per 5 minutes
        return ['guest', 'throttle:3,5'];
    }

    /**
     * @param  ActionRequest  $request
     * @return RedirectResponse
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $this->handle($request->string('email')->value());

        FlashHelper::message('Verification code sent successfully.');

        return back();
    }
}
