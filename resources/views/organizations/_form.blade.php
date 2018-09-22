<div class="row">
    <div class="col-md-12">
        <label>Форма собственности</label>
        <div style="padding-left: 20px;padding-bottom: 10px">
            <label style="display: block;">
                {!! Form::radio('ownership', 'organization', (old('ownership', isset($organization) ? $organization->ownership : '') == 'organization'), ['required'=>'required']) !!}
                Организация
            </label>
            <label>
                {!! Form::radio('ownership', 'ip', (old('ownership', isset($organization) ? $organization->ownership : '') == 'ip'), ['required'=>'required']) !!}
                Индивидуальный предприниматель (ИП)
            </label>
        </div>
    </div>
</div>

@if(!isset($organization))
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-name">Название организации</label>
            {!! Form::text('name', old('name', isset($organization) ? $organization->name : ''), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-name']) !!}
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-ohrn">ОГРН</label>
            {!! Form::text('ohrn', old('ohrn', isset($organization) ? $organization->ohrn : ''), ['class'=>'form-control', 'id' => 'input-ohrn']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-inn">ИНН</label>
            {!! Form::text('inn', old('inn', isset($organization) ? $organization->inn : ''), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-inn']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-kpp">КПП</label>
            {!! Form::text('kpp', old('kpp', isset($organization) ? $organization->kpp : ''), ['class'=>'form-control', 'id' => 'input-kpp']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-address">Юридиеский адрес</label>
            {!! Form::text('address', old('address', isset($organization) ? $organization->address : ''), ['class'=>'form-control', 'id' => 'input-address']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-bank">Банк</label>
            {!! Form::text('bank', old('bank', isset($organization) ? $organization->bank : ''), ['class'=>'form-control', 'id' => 'input-bank']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-name">БИК</label>
            {!! Form::text('bik', old('bik', isset($organization) ? $organization->bik : ''), ['class'=>'form-control', 'id' => 'input-bik']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-bank-account">Расчетный счет</label>
            {!! Form::text('bank_account', old('bank_account', isset($organization) ? $organization->bank_account : ''), ['class'=>'form-control', 'id' => 'input-bank-account']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-correspondent-account">Корреспондентский счет</label>
            {!! Form::text('correspondent_account', old('correspondent_account', isset($organization) ? $organization->correspondent_account : ''), ['class'=>'form-control', 'id' => 'input-correspondent-account']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-contact-person">Контактное лицо</label>
            {!! Form::text('contact_person', old('contact_person', isset($organization) ? $organization->contact_person : ''), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-contact-person']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-email">Ваш e-mail</label>
            {!! Form::text('email', old('email', isset($organization) ? $organization->email : ''), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-email']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-name">Ваш телефон</label>
            {!! Form::text('phone', old('phone', isset($organization) ? $organization->phone : ''), ['class'=>'form-control', 'id' => 'input-phone']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-logo">@lang('organizations.logo')</label><br>

            @if(isset($organization) && $organization->logo())
                <img src="{{ $organization->logo() }}"
                     class="img-thumbnail"
                     alt="{{ $organization->name }}"
                     title="{{ $organization->name }}"
                     style="width: 100px; height: 100px;margin-bottom: 5px;">
            @endif

            <input type="file" name="logo" id="input-logo">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-description">@lang('organizations.description')</label>
            {!! Form::textarea('description', old('description', isset($organization) ? $organization->description : ''), ['class' => 'editor1', 'id' => 'input-description']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-responsive" id="table-files">
            <tbody>
            @php($num = 0)
            @if(isset($organization) && $files = $organization->files)
                @foreach($files as $file)
                    <tr>
                        <td>
                            <input type="hidden" name="files[{{ $num }}][path]" value="{{ $file->path }}">
                            <input type="hidden" name="files[{{ $num }}][title]" value="{{ $file->title }}">
                            {{ $file->title }}
                        </td>
                        <td class="text-right">
                            <button type="button" onclick="$(this).parent().parent().remove();" class="btn btn-danger btn-sm">
                                <i aria-hidden="true" class="fa fa-close"></i>
                            </button>
                        </td>
                    </tr>
                @php($num++)
                @endforeach
                </p>
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        $('#file-upload').on('change', uploadFile);

        function uploadFile(event) {
            var data = new FormData();

            data.append('file', event.target.files[0]);

            $.ajax({
                url: '{{ route('uploader') }}',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                before: function () {
                    $('#organization-form input[type=file]').val();
                },
                success: function (data, textStatus, jqXHR) {
                    if (data.status == 'success') {
                        addFile(data.data.title, data.data.path);
                    } else {
                        alert(data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('ERRORS: ' + textStatus);
                }
            });
        }

        var num = '{{ $num }}';

        function addFile(title, path) {
            var template = '<tr><td><input type="hidden" name="files[' + num + '][path]" value="' + path + '">';

            template += '<input type="hidden" name="files[' + num + '][title]" value="' + title + '">' + title + '</td>';

            template += '<td class="text-right"><button type="button" onclick="$(this).parent().parent().remove();" class="btn btn-danger btn-sm"><i aria-hidden="true" class="fa fa-close"></i></button></td></tr>';

            $('#table-files').find('tbody').append(template);

            num++;
        }
    </script>
@endpush