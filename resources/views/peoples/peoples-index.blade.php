@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
   <div class="row">
      <div class="col-md-12">
         <h2>@lang('peoples.title')</h2>
         <div class="row">
            <div class="col-md-3 search">
               <input type="text" class="form-control" id="login_search" placeholder="@lang('peoples.search')"><i class="fa fa-search" aria-hidden="true"></i>
               <ul class="result"></ul>
            </div>
         </div>
         <table class="table table-responsive table-full-width table-peoples table-hover table-search">
            <thead>
            <tr>
               <th>@lang('peoples.login')</th>
               <th>@lang('peoples.name')</th>
               <th>@lang('peoples.feedbacks')</th>               
               <th>@lang('peoples.member')</th>               
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
               <tr>
                  <td><a href="{{route('peoples.show', $user)}}">{{$user->username}}</a></td>
                  <td>{{$user->name}}</td>
                  <td><span class="text-success">{{$user->rating_positive}}</span>/<span class="text-danger">{{$user->rating_negative}}</span></td>
                  <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y') }}</td>
               </tr>
            @endforeach
            </tbody>
         </table>
         {!! $users->links() !!}
      </div>
   </div>
</div>
@endsection