@component('mail::message')
###The complaint was received
Message user: {{$job->message}}
<hr style="border:dotted 1px #ccc;margin:10px 0;"/>
##Job: {{$job->name}}
{{----}}
{!! $job->desc !!}
{{----}}
{{--**Difficulty:** **{{$job->difficulty->name}}**--}}
{{----}}
@component('mail::button', ['url' => url()->to('/?job='.$job->id)])
View job
@endcomponent
{{----}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent

