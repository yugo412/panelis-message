<x-mail::message>
{{ $body }}

@if ($quoteMessage)
<x-mail::panel>
{{ $message->body }}
</x-mail::panel>
@endif

@lang('Regards,')<br/>
**{{ config('app.name') }}**
</x-mail::message>
