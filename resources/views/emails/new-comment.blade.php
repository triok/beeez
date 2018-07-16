@component('mail::message')
    ##There is a new comment for your task
    {{$job->name}}
    <hr style="border:dotted 1px #ccc;margin:10px 0;"/>
    ##Comment text

    {!! $comment->body !!}

    {{--@component('mail::button', ['url' => route('jobs.show', $job)])--}}
    @component('mail::button', ['url' => url()->to('/jobs/' . $job->id)])
        Go to task
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
