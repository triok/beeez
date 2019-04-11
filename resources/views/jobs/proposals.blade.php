<div class="p-0-top-bottom">
    <div class="offers">
        <h2>@lang('show.offers')</h2>

        @if($job->proposals->count())
            <ul class="media-list">
                @foreach($job->proposals as $proposal)
                    @include('jobs.proposal')
                    
                @endforeach
            </ul>
        @else
            @lang('show.nooffer')
        @endif
    @if(\Carbon\Carbon::now() <= $job->end_date)
        @if(auth()->id() != $job->user_id && !$job->applications->count())
            @if(!$job->proposals()->where('user_id', auth()->id())->count())
                <h2>@lang('show.offers-small')</h2>

                {!! Form::open(['url' => route('job.proposals', $job), 'method'=>'post']) !!}

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea required rows="5" name="body" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                    <div class="form-group">
                        <label for="up_to">Актуально до:</label>
                        <input type="text" name="up_to" class="form-control timepicker-actions" required="required" autocomplete="off" />
                    </div>
                    </div>
                </div>                

                @if(auth()->user()->proposalTeams()->count())
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="proposal_type" class="form-control">
                                <option value="0">От себя</option>
                                @foreach(auth()->user()->proposalTeams() as $team)
                                    <option value="{{ $team->id }}">От имени команды {{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('show.addoffer')</button>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            @endif
        @endif
    @endif    
    </div>
</div>
@push('styles')

    <link rel="stylesheet" href="/css/datepicker.min.css"/>
@endpush

@push('scripts')

    <script src="/js/datepicker.min.js"></script>
    <script>
        $('.timepicker-actions').datepicker({
            timepicker: true,
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