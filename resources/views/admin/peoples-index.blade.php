@extends('layouts.app')
@section('content')

   <div class="row">
      <div class="col-md-12">
         <h2>@lang('peoples.title')</h2>
         <div class="row">
            <div class="col-md-4 col-md-offset-8">
               <input type="text" class="form-control pull-right" id="login_search" placeholder="@lang('peoples.search')">
               <ul class="result"></ul>
            </div>
         </div>
         <table class="table table-striped table-responsive table-full-width">
            <thead>
            <tr>
               <th>@lang('peoples.name')</th>
               <th>@lang('peoples.login')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
               <tr>
                  <td>
                     {{$user->name}}
                     (<span class="text-success">{{$user->rating_positive}}</span>/<span class="text-danger">{{$user->rating_negative}}</span>)
                  </td>
                  <td><a href="{{route('peoples.show', $user)}}">{{$user->username}}</a></td>
               </tr>
            @endforeach
            </tbody>
         </table>
         {!! $users->links() !!}
      </div>
      </div>
   </div>
@endsection