@component('mail::message')

# The user submitted an application for review

Your task <a href="{{route('jobs.show', $job)}}" title="{{$job->name}}">{{$job->name}}</a> has been done!
Please review and pay for <a href="{{route('peoples.show', $user)}}">{{$user->username}}</a> if everything is OK.

@if(isset($request->message) && $request->message != '')
###Message:###

{{ $request->message }}
@endif

@component('mail::button', ['url' => route('jobs.show', $job)])
Review job
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

