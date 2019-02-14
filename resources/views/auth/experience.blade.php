<h2 style="margin: 0 0 0 20px;">@lang('account.experience')</h2>

@foreach(auth()->user()->experiences as $experience)
    <div class="row">
        <div class="col-md-12">
            <div class="base-wrapper">
                <p><strong>Название компании:</strong> <span>{{ $experience->name }}</span></p>
                <p><strong>Должность:</strong> <span>{{ $experience->position }}</span></p>
                <p><strong>Дата зачисления:</strong> <span class="date-short">{{ $experience->hiring_at }}</span></p>
                <p><strong>Дата увольнения:</strong> <span class="date-short">{{ $experience->dismissal_at }}</span></p>
            </div>
        </div>
    </div>
@endforeach

<form action="/account/experiences" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-12">
            <div class="base-wrapper">
                <div class="form-group">
                    <label>Название</label>
                    {!! Form::text('name','',['class'=>'form-control','required'=>'required']) !!}
                </div>

                <div class="form-group">
                    <label>Должность</label>
                    {!! Form::text('position','',['class'=>'form-control','required'=>'required']) !!}
                </div>

                <div class="form-group">
                    <label>Дата зачисления</label>
                    {!! Form::text('hiring_at','',['class'=>'form-control timepicker-actions','required'=>'required']) !!}
                </div>

                <div class="form-group">
                    <label>Дата увольнения</label>
                    {!! Form::text('dismissal_at','',['class'=>'form-control timepicker-actions']) !!}
                </div>

                <div class="form-group">
                    <button class="btn btn-primary">
                        @lang('account.save')
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('styles')
    <link rel="stylesheet" href="/css/custom.css"/>
    <link rel="stylesheet" href="/css/datepicker.min.css"/>
@endpush

@push('scripts')
    <script src="/js/custom.js"></script>
    <script src="/js/datepicker.min.js"></script>
    <script>
        $('.timepicker-actions').datepicker({
            timepicker: false,
            startDate: new Date(),
            minHours: 9,
            maxHours: 24,
            minDate: new Date(),
            onSelect: function (fd, d, picker) {
                if (!d) return;

                picker.update({
                    minHours: 0,
                    maxHours: 24
                })
            }
        });
    </script>
@endpush

