@extends('layouts.app')
@section('content')
   <div class="row">
      <div class="col-md-12">
         <h2>Pages</h2>
         <form action="{{ url('admin/create-page') }}">
            <button class="btn btn-success">New page</button>
         </form>
         <table class="table table-hover">
            <thead>
               <tr>
                  <td>Name</td>
                  <td>Action</td>
               </tr>
            </thead>
            <tbody>
               @foreach ($pages as $page)
                  <tr>
                     <td><a href="/page/{{ $page->id }}">{{ $page->title }}</a></td>
                     <td style="display: flex;"><form method="get" action="edit/{{ $page->id }}"><button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></button></form>&nbsp;<form method="get" action="delete/{{ $page->id }}" onclick="return confirm('Are you sure to delete this page?')"><button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></form></td>
                  </tr>
               @endforeach   
            </tbody>
         </table>
      </div>
   </div>
@endsection