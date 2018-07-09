@extends('layouts.app')
@section('content')

   <div class="row">
      <div class="col-md-12">
         <h2>Peoples</h2>
         <div class="row">
            <div class="col-md-4 col-md-offset-8">
               <input type="text" class="form-control pull-right" id="login_search" placeholder="Enter login ...">
               <ul class="result"></ul>
            </div>
         </div>
         <table class="table table-striped table-responsive table-full-width">
            <thead>
            <tr>
               <th>Name</th>
               <th>Login</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
               <tr>
                  <td>{{$user->name}}</td>
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