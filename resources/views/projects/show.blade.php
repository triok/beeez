@extends('layouts.app')

@section('content')

<div class="container-fluid show-project" id="main">
    <div class="col-xs-12">
    <a href="{{route('projects.index')}}"><span><i class="fa fa-arrow-left"></i> @lang('projects.show-back')</span></a>
    </div>
    <div class="col-sm-3">
        <h2>{{ $project->name }}</h2>
        <br>
        <div class="text">
        <textarea cols="50" rows="20" placeholder="@lang('projects.notes')">
            
        </textarea>   
    </div>
    </div>
    
    <div class="col-xs-9">
    <div class="col-sm-2">
    <a href="{{ route('jobs.create') }}?project_id={{ $project->id }}" class="btn btn-block btn-primary" style="margin-top: 10px;">@lang('projects.post')</a>
    </div>

    <div class="project-container col-xs-12">
            @if(count($project->jobs) > 0)  
        <table class="table table-sm table-hover">

            <thead>
                <th>@lang('projects.job-name')</th>
                <th>@lang('projects.deadline')</th>
                <th>@lang('projects.executor')</th>
                <th>@lang('projects.price')</th>
                <th>@lang('projects.publish')</th>
            </thead>
 
            <tbody class="sortable-rows">
 
            @foreach($project->jobs as $job)
            <tr class="sort-row" id="{{ $job->id }}">
                <td><a href="{{ route('jobs.show', $job) }}">{{ $job->name }}</a></td>
                <td class="date-short">{{ \Carbon\Carbon::parse($job->end_date) }}</td>
                <td>
                    @if(count($job->applications) > 0)
                        {{ $job->applications()->first()->user->name }}
                    @else
                        @lang('projects.nousers')
                    @endif
                </td>
                <td>{{ $job->formattedPrice }}</td>
                <td>@lang('projects.published')</td>
            </tr>
            @endforeach

            </tbody>
          
        </table>
            @else
            <p>@lang('projects.notask')</p>
            @endif  
    </div>

    <div class="pull-right">
        <p>
            <span>@lang('projects.jobs-total')</span>
            {{ $project->jobs()->count() }}<span>@lang('projects.summ-total')</span>
            {{ $totalPrice }}
        </p>
        <p><span>@lang('projects.jobs-complete')</span> 0</p>
    </div>
</div>
</div>
   
@endsection

@push('scripts')
    <script src="/js/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $(".sortable-rows").sortable({
                placeholder: "ui-state-highlight",
                update: function (event, ui) {
                    updateDisplayOrder();
                }
            });
        });

        // function to save display sort order
        function updateDisplayOrder() {
            var selectedLanguage = [];
            $('.sortable-rows .sort-row').each(function () {
                selectedLanguage.push($(this).attr("id"));
            });
            var dataString = 'sort_order=' + selectedLanguage;
            $.ajax({
                type: "POST",
                url: "/order-project-jobs",
                data: dataString,
                cache: false,
                success: function (data) {
                }
            });
        }
    </script>
@endpush
