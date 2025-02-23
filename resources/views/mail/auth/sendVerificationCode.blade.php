<x-mail::message>
    # Email Verification Code

    Hi {{ $email }}, to continue setting up your account, please verify your
    account with the code below:

    ## Verification Code

    ### {{ $verificationCode }}

    *This code will expire in 3 hours. Please do not disclose this code to
    others. If you did not make this request, please disregard this email.*

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
