@component('mail::message')
# @lang('messages.link.generated')

@lang('messages.link.valid.date'): {{ $validUntil }}

@component('mail::button', ['url' => $url])
@lang('messages.link.open')
@endcomponent

@endcomponent
