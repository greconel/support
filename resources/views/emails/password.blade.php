@component('mail::message')
# A new password has been created for you

Change your password immediately for security reasons! <br> <br>

@component('mail::panel')
Email: {{ $user->email }} <br>
Password: {{ $password }}
@endcomponent

@component('mail::button', ['url' => route('login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
