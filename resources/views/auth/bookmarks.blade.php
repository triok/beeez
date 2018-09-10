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
            </div>
            <div id="messages" class="tab-pane fade">
            </div>                                                                
        </div>
    </div>
</div>
@endsection