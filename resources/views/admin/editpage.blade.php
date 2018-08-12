@extends('layouts.app')
@section('content')
   <div class="row">
      <div class="col-xs-12">
         <h2>Edit page: {{ $page->title }}</h2>
         {{ Form::open(['url' => 'admin/update-page/'.$page->id ]) }}
          <div class="form-group">
            {{ Form::label ('title', 'Title') }}
            {{ Form::text ('title', $page->title, ['class' => 'form-control']) }}
          </div>
          <div class="form-group">
            {{ Form::label ('description', 'Description') }}
            {{ Form::textarea ('description', $page->description, ['class' => 'editor2 form-control']) }}
          </div>    
          <div class="form-group">
            {{ Form::submit('Update page', ['class' => 'btn btn-primary']) }}
          </div>                
         {{ Form::close() }}
     </div>
   </div>
@include('partials.tinymce',['editor'=>'.editor2'])
@endsection