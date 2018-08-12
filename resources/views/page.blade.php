@extends('layouts.app')
@section('content')
   <div class="row">
      <div class="col-md-12">
         <h2>{{ $page->title }}</h2>
         <div>{!! $page->description !!}</div>
      </div>
   </div>
@endsection