@component('mail::message')
{{$organization->name}}
<hr style="border:dotted 1px #ccc;margin:10px 0;"/>

{!! $organization->description !!}

@component('mail::button', ['url' => route('organizations.moderation', $organization)])
View organization
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
