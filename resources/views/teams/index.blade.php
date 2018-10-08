@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
   <div class="row">
      <div class="col-md-12 teams">
         <h2>@lang('teams.title')</h2>
         <div class="row">
            <div class="col-md-4 search">
               <input type="text" class="form-control" id="team_search" placeholder="@lang('teams.search')"><i class="fa fa-search" aria-hidden="true"></i>
               <ul class="result"></ul>
            </div>
            <div class="col-md-8 search-button">
               <a href="{{ route('teams.create') }}" class="btn btn-primary btn-md pull-right">
                  <i class="fa fa-plus-circle"></i> @lang('teams.create_team')
               </a>
            </div>
         </div>
         <table class="table table-striped table-responsive table-full-width table-search">
            <thead>
            <tr>
               <th>@lang('teams.team')</th>
               <th>@lang('teams.owner')</th>
               <th>@lang('teams.show_team_type')</th>
               <th>@lang('teams.show_date')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($teams as $team)
               <tr>
                  <td>
                     <a href="{{ route('teams.show', $team) }}">{{$team->name}}</a>

                     @if(auth()->id() == $team->user_id)
                        <i class="fa fa-star"></i>
                     @endif
                  </td>
                  <td>
                     <a href="{{ route('peoples.show', $team->user) }}">{{$team->user->name}}</a>
                  </td>
                  <td>{{ $team->type->name }}</td>
                  <td class="date-short">{{ $team->created_at }}</td>
               </tr>
            @endforeach

            </tbody>
         </table>
         {!! $teams->links() !!}
      </div>
   </div>
</div>
@endsection