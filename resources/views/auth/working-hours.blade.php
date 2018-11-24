<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Редактирование графика</label><br>
            <small>Выберите рабочие/нерабочие дни т укажите время работы</small>
        </div>
    </div>
</div>

@php($working_hours = $user->working_hours ? json_decode($user->working_hours, true) : [])

@foreach(config('enums.days') as $day=>$num)
    <div class="row">
        <div class="col-md-1" style="padding: 8px 0px 0px 15px;">
            <label>@lang('account.days.' . $day)</label>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <select class="form-control day-start" name="day[{{ $num }}][start]">
                    <option value="0">выходной</option>

                    @for($i=0;$i<=23;$i++)
                        @foreach(['00', '30'] as $j)
                            @if(isset($working_hours[$num]) && $working_hours[$num]['start'] == $i.':'.$j)
                                <option selected value="{{$i}}:00">{{$i}}:{{$j}}</option>
                            @else
                                <option value="{{$i}}:00">{{$i}}:{{$j}}</option>
                            @endif
                        @endforeach
                    @endfor
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <select class="form-control day-end {{ (isset($working_hours[$num]) && $working_hours[$num]['start'] ? '' : 'hide') }}"
                        name="day[{{ $num }}][end]">
                    <option value="0"></option>

                    @for($i=0;$i<=23;$i++)
                        @foreach(['00', '30'] as $j)
                            @if(isset($working_hours[$num]) && $working_hours[$num]['end'] == $i.':'.$j)
                                <option selected value="{{$i}}:00">{{$i}}:{{$j}}</option>
                            @else
                                <option value="{{$i}}:00">{{$i}}:{{$j}}</option>
                            @endif
                        @endforeach
                    @endfor
                </select>
            </div>
        </div>

        <div class="col-md-1" style="padding-top: 8px;">
            <i class="fa fa-calendar-times-o {{ (isset($working_hours[$num]) && $working_hours[$num]['start'] ? 'hide' : '') }}"></i>
            <i class="fa fa-calendar-check-o {{ (isset($working_hours[$num]) && $working_hours[$num]['start'] ? '' : 'hide') }}"></i>
        </div>
    </div>
@endforeach

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="checkbox" name="show_working_hours" id="input-show-working-hours" value="1"
                    {{ ($user->show_working_hours ? 'checked' : '') }}>
            <label for="input-show-working-hours">Отображать график</label>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function () {
            $('.day-start').on('change', function () {
                var row = $(this).parent().parent().parent();

                if ($(this).val() == '0') {
                    row.find('.day-end').addClass('hide');

                    row.find('.fa-calendar-check-o').addClass('hide');
                    row.find('.fa-calendar-times-o').removeClass('hide');
                } else {
                    row.find('.day-end').removeClass('hide');

                    row.find('.fa-calendar-times-o').addClass('hide');
                    row.find('.fa-calendar-check-o').removeClass('hide');
                }
            });
        });
    </script>
@endpush