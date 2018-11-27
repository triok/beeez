@extends('layouts.app')

@section('content')
<div class="container-fluid projects" id="main">
    <div class="col-xs-3">
    <h2>@lang('projects.title')</h2>
    <div>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque rerum possimus culpa deleniti molestiae vitae nisi ut reprehenderit nemo, dicta dignissimos libero nesciunt, impedit temporibus beatae eos, harum earum?</p> 
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, voluptate inventore voluptatibus non quam tempora aliquid provident sequi eveniet? Magni esse beatae culpa tenetur deserunt? Explicabo at cupiditate deleniti magnam?</p>
    <p class="pull-right">
        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-block">
            <i class="fa fa-sitemap"></i> @lang('projects.create')
        </a>
    </p>            
    </div>
    </div>
    <div class="col-xs-9">

    <div class="base-wrapper">

    <ul class="nav nav-tabs">
      <li role="presentation" class="active"><a data-toggle="tab" href="#current"><i class="fa fa-clock-o" aria-hidden="true"></i> @lang('projects.current')</a></li>
      <li role="presentation"><a data-toggle="tab" href="#completed"><i class="fa fa-check-square-o" aria-hidden="true"></i> @lang('projects.completed')</a></li>
      <li role="presentation"><a data-toggle="tab" href="#favorite"><i class="fa fa-star-o" aria-hidden="true"></i> @lang('projects.favorite')</a></li>
    </ul>

    <div class="tab-content">
        <div id="current" class="tab-pane fade in active">    
            <table class="table table-responsive table-projects">
                <thead>
                    <th> </th>
                    <th>@lang('projects.name')</th>
                    <th>@lang('projects.desc')</th>
                    <th>@lang('projects.deadline')</th>                    
                    <th>@lang('projects.count')</th>
                    <th></th>
                </thead>

                <tbody class="sortable-rows">
                @php($projectActiveCount = 0)

                @foreach($projects as $project)
                    @if(!$project->isArchived())
                        <tr class="sort-row" id="{{ $project->id }}">
                            <td>
                                @if($project->icon)
                                    <i class="fa {{ $project->icon }}"></i>
                                @endif
                            </td>
                            <td><a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a></td>
                            <td>{{ $project->description }}</td>
                            <td class="date-long">{{ $project->deadline_at }}</td>
                            <td>{{ $project->jobs()->count() }}/0</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        {!! Form::open(['url' => route('projects.edit', $project), 'method'=>'post']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-primary btn-round" title="@lang('projects.edit')">
                                            <i class="fa fa-pencil fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}

                                        @if(!$project->isFavorited())
                                            {!! Form::open(['url' => route('projects.favorite', $project), 'method'=>'post']) !!}
                                            <button type="submit" onclick="" class="btn btn-xs btn-default btn-round" title="@lang('projects.favorite_add')">
                                                <i class="fa fa-star-o fa-fw"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif

                                        @if($project->isFavorited())
                                            {!! Form::open(['url' => route('projects.unfavorite', $project), 'method'=>'post']) !!}
                                            <button type="submit" onclick="" class="btn btn-xs btn-default btn-round" title="@lang('projects.favorite_del')">
                                                <i class="fa fa-star fa-fw" style="color: orange;"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif

                                        {!! Form::open(['url' => route('projects.done', $project), 'method'=>'post', 'class' => 'form-delete']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-success btn-round" title="@lang('projects.complete')">
                                            <i class="fa fa-check fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}

                                        {!! Form::open(['url' => route('projects.destroy', $project), 'method'=>'delete', 'class' => 'form-delete']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-danger btn-round" title="@lang('projects.delete')">
                                            <i class="fa fa-trash fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @php($projectActiveCount++)
                    @endif
                @endforeach

                @if($projectActiveCount == 0)
                    <tr>
                        <td colspan="5">
                            @lang('projects.noprojects')
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div id="completed" class="tab-pane fade ">
            <table class="table table-responsive table-projects">
                <thead>
                    <th> </th>                    
                    <th>@lang('projects.name')</th>
                    <th>@lang('projects.desc')</th>
                    <th>@lang('projects.deadline')</th>
                    <th>@lang('projects.count')</th>
                    <th></th>
                </thead>
                <tbody class="sortable-rows">
                @php($projectArchivedCount = 0)

                @foreach($projects as $project)
                    @if($project->isArchived())
                        <tr class="sort-row" id="{{ $project->id }}">
                            <td>
                                @if($project->icon)
                                    <i class="fa {{ $project->icon }}"></i>
                                @endif
                            </td>
                            <td><a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a></td>
                            <td>{{ $project->description }}</td>
                            <td class="date-long">{{ $project->deadline_at }}</td>
                            <td>{{ $project->jobs()->count() }}/0</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        {!! Form::open(['url' => route('projects.edit', $project), 'method'=>'post']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-primary btn-round" title="@lang('projects.edit')">
                                            <i class="fa fa-pencil fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}

                                        {!! Form::open(['url' => route('projects.restore', $project), 'method'=>'post', 'class' => 'form-delete']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-warning btn-round" title="@lang('projects.restore')">
                                            <i class="fa fa-refresh fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}

                                        {!! Form::open(['url' => route('projects.destroy', $project), 'method'=>'delete', 'class' => 'form-delete']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-danger btn-round" title="@lang('projects.delete')">
                                            <i class="fa fa-trash fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}                                
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @php($projectArchivedCount++)
                    @endif
                @endforeach
                @if($projectArchivedCount == 0)
                    <tr>
                        <td colspan="5">
                            @lang('projects.nocompprojects')
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>                   
        </div>
        <div id="favorite" class="tab-pane fade">
            <table class="table table-responsive table-projects">
                <thead>
                <tr>
                    <th> </th>
                    <th>@lang('projects.name')</th>
                    <th>@lang('projects.desc')</th>
                    <th>@lang('projects.deadline')</th>
                    <th>@lang('projects.count')</th>
                    <th></th>
                </tr>
                </thead>

                <tbody class="sortable-rows">
                @php($projectFavoritedCount = 0)

                @foreach($projects as $project)
                    @if(!$project->isArchived() && $project->isFavorited())
                        <tr class="sort-row" id="{{ $project->id }}">
                            <td>
                                @if($project->icon)
                                    <i class="fa {{ $project->icon }}"></i>
                                @endif
                            </td>
                            <td><a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a></td>
                            <td>{{ $project->description }}</td>
                            <td class="date-long">{{ $project->deadline_at }}</td>
                            <td>{{ $project->jobs()->count() }}/0</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">                                
                                        {!! Form::open(['url' => route('projects.edit', $project), 'method'=>'post']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-primary btn-round" title="@lang('projects.edit')">
                                            <i class="fa fa-pencil fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}

                                        {!! Form::open(['url' => route('projects.unfavorite', $project), 'method'=>'post']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-default btn-round" title="@lang('projects.favorite_del')">
                                            <i class="fa fa-star fa-fw" style="color: orange;"></i>
                                        </button>
                                        {!! Form::close() !!}

                                        {!! Form::open(['url' => route('projects.done', $project), 'method'=>'post', 'class' => 'form-delete']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-success btn-round" title="@lang('projects.complete')">
                                            <i class="fa fa-check fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}

                                        {!! Form::open(['url' => route('projects.destroy', $project), 'method'=>'delete', 'class' => 'form-delete']) !!}
                                        <button type="submit" onclick="" class="btn btn-xs btn-danger btn-round" title="@lang('projects.delete')">
                                            <i class="fa fa-trash fa-fw"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @php($projectFavoritedCount++)
                    @endif
                @endforeach

                @if($projectFavoritedCount == 0)
                    <tr>
                        <td colspan="5">
                            @lang('projects.nofavprojects')
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
