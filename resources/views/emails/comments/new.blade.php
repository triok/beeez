@component('mail::message')
# There is a new comment for your task <a href="{{route('jobs.show', $job)}}" style="color: #37A000; font-weight: bold; text-decoration: none;">{{$job->name}}</a>

<hr>
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
    <tbody>
    <tr>
        <td valign="top" width="60" style="padding-right:20px;">
            <div style="width:60px;height:60px;overflow:hidden;border-radius:30px;">
                <img height="60" alt="{{auth()->user()->name}}" src="{{ url(auth()->user()->getStorageDir() . auth()->user()->avatar)}}">
            </div></td>
        <td valign="top" width="100%" style="vertical-align:top;font-family:Helvetica,arial,sans-serif;font-size:16px;color:#222222;text-align:left;line-height:20px;border-collapse:collapse;" st-content="rightimage-paragraph" align="left">
            <div style="font-family:&quot;Gotham SSm&quot;,Helvetica,arial,sans-serif;font-size:16px;color:#222222;">
                <strong>{{auth()->user()->username}}</strong>
            </div>
            <div style="font-size:12px;color:#7d7d7d;margin:0 0;padding-bottom:15px;">

                {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i A T, d M Y') }}
            </div>
            <div style="line-height:24px;">
                {{str_limit($comment->body, 30)}}
            </div>
        </td>
    </tr>
    <tr>
        <td width="100%" height="30" style="border-collapse:collapse;" colspan="2"></td>
    </tr>
    </tbody>
</table>

@component('mail::button', ['url' => route('jobs.show', $job)])
    Go to task
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
