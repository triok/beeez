@extends('layouts.app')
@section('content')

   <div class="row">
      <div class="col-md-12">
         <h2>@lang('teams.title')</h2>

         <div class="row">
            <div class="col-md-8">
               <a href="{{ route('teams.create') }}" class="btn btn-default btn-xs">
                  <i class="fa fa-plus-circle"></i> @lang('teams.create_team')
               </a>
            </div>

            <div class="col-md-4">
               <input type="text" class="form-control pull-right" id="login_search" placeholder="@lang('teams.search')">
               <ul class="result"></ul>
            </div>
         </div>
         <table class="table table-striped table-responsive table-full-width">
            <thead>
            <tr>
               <th>@lang('teams.team')</th>
               <th>@lang('teams.owner')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($teams as $team)
               <tr>
                  <td><a href="{{ route('teams.show', $team) }}">{{$team->name}}</a></td>
                  <td><a href="{{ route('peoples.show', $team->user) }}">{{$team->user->name}}</a></td>
               </tr>
            @endforeach
            </tbody>
         </table>
         {!! $teams->links() !!}
      </div>
      </div>
   </div>
@endsection