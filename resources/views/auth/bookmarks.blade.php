@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
    <h2>@lang('bookmarks.title')</h2>
    <div class="row">
        <ul class="nav nav-tabs">
          <li role="presentation" class="active"><a data-toggle="tab" href="#jobs">@lang('bookmarks.jobs')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#peoples">@lang('bookmarks.peoples')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#teams">@lang('bookmarks.teams')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#companies">@lang('bookmarks.companies')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#projects">@lang('bookmarks.projects')</a></li>
          <li role="presentation"><a data-toggle="tab" href="#messages">@lang('bookmarks.messages')</a></li>                      
        </ul>

        <div class="tab-content">
            <div id="jobs" class="tab-pane fade in active">
            </div>
            <div id="peoples" class="tab-pane fade">
            </div>
            <div id="teams" class="tab-pane fade in active">
            </div>
            <div id="companies" class="tab-pane fade">
            </div>
            <div id="projects" class="tab-pane fade">
                <div class="row" style="margin: 10px">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a data-toggle="tab" href="#panel1">@lang('projects.current')</a></li>
                    <li role="presentation"><a data-toggle="tab" href="#favorite">Избранное</a></li>
                    <li role="presentation"><a data-toggle="tab" href="#panel2">@lang('projects.completed')</a></li>
                </ul>

                        <div class="tab-content">
                    <div id="panel1" class="tab-pane fade in active">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <td style="width: 20px;"> </td>
                                <td>@lang('projects.name')</td>
                                <td>@lang('projects.desc')</td>
                                <td style="min-width: 200px;">@lang('projects.count')</td>
                                <td></td>
                            </tr>
                            </thead>

                            <tbody class="sortable-rows">
                            @if($projects->count())
                                @foreach($projects as $project)
                                    @if(!$project->is_archived)
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
                    <div id="favorite" class="tab-pane fade">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <td style="width: 20px;"> </td>
                                <td>@lang('projects.name')</td>
                                <td>@lang('projects.desc')</td>
                                <td style="min-width: 200px;">@lang('projects.count')</td>
                                <td></td>
                            </tr>
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
                    <div id="panel2" class="tab-pane fade ">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <td>@lang('projects.name')</td>
                                <td>@lang('projects.desc')</td>
                                <td style="min-width: 200px;">@lang('projects.count')</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody class="sortable-rows">
                            @if($projects->count())
                                @foreach($projects as $project)
                                    @if($project->is_archived)
                                        <tr class="sort-row" id="{{ $project->id }}">
                                            <td><a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a></td>
                                            <td>{{ $project->description }}</td>
                                            <td>{{ $project->jobs()->count() }}/0</td>
                                            <td class="text-right">
                                                <a href="{{ route('projects.edit', $project) }}">
                                                    <i class="fa fa-pencil btn btn-xs btn-default"></i>
                                                </a>

                                                {!! Form::open(['url' => route('projects.restore', $project), 'method'=>'post', 'class' => 'form-delete', 'style' => 'display:inline-block;']) !!}
                                                <input type="hidden" name="redirect" value="my-bookmarks#projects">
                                                <button type="submit" onclick="" class="btn btn-xs btn-warning" title="Возобновить">
                                                    <i class="fa fa-refresh"></i>
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
            <div id="messages" class="tab-pane fade">
            </div>                                                                
        </div>
    </div>
</div>
@endsection