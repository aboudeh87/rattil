{{ trans('emails.welcome') }} {{ $user->name }},
<br/>
<br/>
<p>
    {{ trans('emails.activation_header') }}
</p>

<br>

<a href="{{ action('Auth\RegisterController@activate', [$user->activation_token]) }}">
    {{ action('Auth\RegisterController@activate', [$user->activation_token]) }}
</a>

<br>

<p>
    {{ trans('emails.rattil_team') }}
</p>