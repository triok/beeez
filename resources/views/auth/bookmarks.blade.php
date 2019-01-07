@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
    <h2>@lang('bookmarks.title')</h2>
        <div class="col-xs-6">
            <div class="base-wrapper">
                @lang('bookmarks.jobs')
                @include('applications.partials.favorite', ['class' => 'in active'])
            </div>
            <div class="base-wrapper">
                @lang('bookmarks.projects')
                <div class="row" style="margin: 10px">
                    <div class="col-md-12">

                        <div id="favorite" class="tab-pane fade in active">
                        <table class="table table-responsive">
                            <thead>

                                <th> </th>
                                <th>@lang('projects.name')</th>
                                <th>@lang('projects.desc')</th>
                                <th>@lang('projects.count')</th>
                                <th></th>

                            </thead>
                            <tbody class="sortable-rows">
                            @if($projects->count())
                                @foreach($projects as $project)
                                    @if($project->is_favorite)
                                        <tr class="sort-row" id="{{ $project->id }}">
                                            <td>
                                                @if($project->icon)
                                                    <i class="fa {{ $project->icon }}"></i>
                                                @endif
                                            </td>
                                            <td><a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a></td>
                                            <td>{{ $project->description }}</td>
                                            <td>{{ $project->jobs()->count() }}/0</td>
                                            <td class="text-right">
                                                <a href="{{ route('projects.edit', $project) }}">
                                                    <i class="fa fa-pencil btn btn-xs btn-default"></i>
                                                </a>

                                                @if(!$project->is_favorite)
                                                    {!! Form::open(['url' => route('projects.favorite', $project), 'method'=>'post', 'style' => 'display:inline-block;']) !!}
                                                    <input type="hidden" name="redirect" value="my-bookmarks#projects">
                                                    <button type="submit" onclick="" class="btn btn-xs btn-default" title="Избранный">
                                                        <i class="fa fa-star-o"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif

                                                @if($project->is_favorite)
                                                    {!! Form::open(['url' => route('projects.unfavorite', $project), 'method'=>'post', 'style' => 'display:inline-block;']) !!}
                                                    <input type="hidden" name="redirect" value="my-bookmarks#projects">
                                                    <button type="submit" onclick="" class="btn btn-xs btn-default" title="Удалить с избранных">
                                                        <i class="fa fa-star" style="color: orange;"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif

                                                {!! Form::open(['url' => route('projects.done', $project), 'method'=>'post', 'class' => 'form-delete', 'style' => 'display:inline-block;']) !!}
                                                <input type="hidden" name="redirect" value="my-bookmarks#projects">
                                                <button type="submit" onclick="" class="btn btn-xs btn-success" title="Выполнено">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                {!! Form::close() !!}

                                                {!! Form::open(['url' => route('projects.destroy', $project), 'method'=>'delete', 'class' => 'form-delete', 'style' => 'display:inline-block;']) !!}
                                                <input type="hidden" name="redirect" value="my-bookmarks#projects">
                                                <button type="submit" onclick="" class="btn btn-xs btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">
                                        @lang('projects.noprojects')
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        </div>

                    </div>
                </div>                
            </div>
        </div>
        <div class="col-xs-2" id="peoples">
            <div class="base-wrapper">
                @lang('bookmarks.peoples')
            </div>
        </div>
        <div class="col-xs-2">
            <div class="base-wrapper">
                @lang('bookmarks.teams')
            </div>
        </div>
        <div class="col-xs-2">
            <div class="base-wrapper">
                @lang('bookmarks.companies')
            </div>
        </div>                

        <div class="col-xs-6">
            <div class="base-wrapper">
                @lang('bookmarks.vacancies')
                <div id="vacancies">
                    @include('vacancies.partials.table', ['id' => 'favorite', 'action' => '/api/vacancies/search?favorite=true'])
                </div>                
            </div>
        </div>  
        <div class="col-xs-4">
            <div class="base-wrapper">
                @lang('bookmarks.messages')
            </div>
        </div>          
        <div class="clearfix"></div>     
        <ul class="nav nav-tabs">
          <li role="presentation" class="active"><a data-toggle="tab" href="#favorite">@lang('bookmarks.jobs')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#peoples"></a></li>
          <li role="presentation"><a data-toggle="tab" href="#teams">@lang('bookmarks.teams')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#companies">@lang('bookmarks.companies')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#projects">@lang('bookmarks.projects')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#vacancies">@lang('bookmarks.vacancies')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#messages">@lang('bookmarks.messages')</a></li>
        </ul>

        <div class="tab-content bookmarks"> 


            <div id="teams" class="tab-pane fade">
                <div class="col-xs-12">
                <h2>@lang('bookmarks.heading-teams')</h2>
                @include('teams.partials.table', ['action' => '/api/teams/search?favorite=true'])
                </div>
            </div>
            <div id="companies" class="tab-pane fade">
                <div class="col-xs-12">
                <h2>@lang('bookmarks.heading-org')</h2>
                </div>               
            </div>
            <div id="projects" class="tab-pane fade">

            </div>

            <div id="messages" class="tab-pane fade">
            </div>                                                                
        </div>

</div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush