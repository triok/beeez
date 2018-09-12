@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
   <div class="row">
      <div class="col-md-12">
         <h2>@lang('teams.title')</h2>

         <div class="row">


            <div class="col-md-4">
               <input type="text" class="form-control pull-right" id="team_search" placeholder="@lang('teams.search')">
               <ul class="result"></ul>
            </div>
            <div class="col-md-8">
               <a href="{{ route('teams.create') }}" class="btn btn-default btn-md pull-right">
                  <i class="fa fa-plus-circle"></i> @lang('teams.create_team')
               </a>
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
                  <td>
                     <a href="{{ route('teams.show', $team) }}">{{$team->name}}</a>

                     @if(auth()->id() == $team->user_id)
                        <i class="fa fa-star"></i>
                     @endif
                  </td>
                  <td>
                     <a href="{{ route('peoples.show', $team->user) }}">{{$team->user->name}}</a>
                  </td>
               </tr>
            @endforeach

            </tbody>
         </table>
         {!! $teams->links() !!}
      </div>
   </div>
</div>
@endsection