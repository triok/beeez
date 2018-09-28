@extends('layouts.app')

@section('content')
<div class="container-fluid projects" id="main">
    <h2>@lang('projects.title')</h2>

    <div class="alert alert-info">
        Drag to order
    </div>

    <div class="col-sm-3 pull-right">
        <a href="{{ route('projects.create') }}" class="btn btn-success btn-block">
            <i class="fa fa-sitemap"></i> @lang('projects.create')
        </a>
    </div>

    <ul class="nav nav-tabs">
      <li role="presentation" class="active"><a data-toggle="tab" href="#panel1">@lang('projects.current')</a></li>
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

                                {!! Form::open(['url' => route('projects.done', $project), 'method'=>'post', 'class' => 'form-delete']) !!}
                                <button type="submit" onclick="" class="btn btn-xs btn-success" title="Выполнено">
                                    <i class="fa fa-check"></i>
                                </button>
                                {!! Form::close() !!}

                                {!! Form::open(['url' => route('projects.destroy', $project), 'method'=>'delete', 'class' => 'form-delete']) !!}
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

                                    {!! Form::open(['url' => route('projects.restore', $project), 'method'=>'post', 'class' => 'form-delete']) !!}
                                    <button type="submit" onclick="" class="btn btn-xs btn-warning" title="Возобновить">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                    {!! Form::close() !!}

                                    {!! Form::open(['url' => route('projects.destroy', $project), 'method'=>'delete', 'class' => 'form-delete']) !!}
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
@endsection

@push('scripts')
    <script src="/js/custom.js"></script>
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
                url: "/order-projects",
                data: dataString,
                cache: false,
                success: function (data) {
                }
            });
        }
    </script>
@endpush
