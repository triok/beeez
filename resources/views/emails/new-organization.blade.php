@component('mail::message')
{{$organization->name}}
<hr style="border:dotted 1px #ccc;margin:10px 0;"/>

<p>
    <b>ОГРН:</b>
    <span>{{ $organization->ohrn }}</span>
</p>

<p>
    <b>ИНН:</b>
    <span>{{ $organization->inn }}</span>
</p>

<p>
    <b>КПП:</b>
    <span>{{ $organization->kpp }}</span>
</p>

<p>
    <b>Юридиеский адрес:</b>
    <span>{{ $organization->address }}</span>
</p>

<p>
    <b>Банк:</b>
    <span>{{ $organization->bank }}</span>
</p>

<p>
    <b>БИК:</b>
    <span>{{ $organization->bik }}</span>
</p>

<p>
    <b>Расчетный счет:</b>
    <span>{{ $organization->bank_account }}</span>
</p>

<p>
    <b>Корреспондентский счет:</b>
    <span>{{ $organization->correspondent_account }}</span>
</p>

<p>
    <b>Контактное лицо:</b>
    <span>{{ $organization->contact_person }}</span>
</p>

<p>
    <b>Ваш e-mail:</b>
    <span>{{ $organization->email }}</span>
</p>

<p>
    <b>Ваш телефон:</b>
    <span>{{ $organization->phone }}</span>
</p>

<p>
    <b>@lang('organizations.show_description')</b>
    {!! $organization->description !!}
</p>

@component('mail::button', ['url' => route('organizations.show', $organization)])
View organization
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
