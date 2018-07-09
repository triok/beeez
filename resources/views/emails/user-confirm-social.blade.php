@component('mail::message')
# User want to confirm social account

*Name:*  {{Auth::user()->name}} <br/>
*Login:* {{Auth::user()->username}} <br/>
*Email:* {{Auth::user()->email}}
<br/>

@component('mail::button', ['url' => $request->value])
{{$request->attr}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
