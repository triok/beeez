<div class="air-card p-0-top-bottom">
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

        @if(auth()->id() != $job->user_id && !$job->applications->count())
            @if(!$job->proposals()->where('user_id', auth()->id())->count())
                <h2>@lang('show.offers-small')</h2>

                {!! Form::open(['url' => route('job.proposals', $job), 'method'=>'post']) !!}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <textarea required rows="5" name="body" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->ownTeams)
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="proposal_type" class="form-control">
                                <option value="0">От себя</option>
                                @foreach(auth()->user()->ownTeams as $team)
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
    </div>
</div>