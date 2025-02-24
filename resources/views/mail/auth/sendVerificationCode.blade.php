<x-mail::message>
    # Hi, {{ $email }}

    To continue setting up your account, please verify your
    account with the code below:

    {{ $verificationCode }}

    This code will expire in 3 hours. Please do not disclose this code to
    others. If you did not make this request, please disregard this email.

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
