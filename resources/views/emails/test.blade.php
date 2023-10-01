<x-mail::message>
# Verify Your New Account

Please verify your new account by clicking on below button
hello {{ $user->name }}

<x-mail::button :url="route('verify-email',$user->verification_token)">
Verify Your Account
</x-mail::button>

Thanks,<br/>
{{ config('app.name') }}
</x-mail::message>
