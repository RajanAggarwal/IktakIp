@component('mail::message')
# Hello,
Welcome to {{ config('app.name') }}.
Please click on the bellow button to verify your email.

@component('mail::button', ['url' => $link])
Activate account
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent