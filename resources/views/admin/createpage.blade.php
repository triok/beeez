@extends('layouts.app')
@section('content')
   <div class="row">
      <div class="col-xs-12">
         <h2>Create new page</h2>
         {{ Form::open(['url' => 'admin/store-page']) }}
          <div class="form-group">
            {{ Form::label ('title', 'Title') }}
            {{ Form::text ('title', null, ['class' => 'form-control']) }}
          </div>
          <div class="form-group">
            {{ Form::label ('description', 'Description') }}
            {{ Form::textarea ('description', null, ['class' => 'editor2 form-control']) }}
          </div>    
          <div class="form-group">
            {{ Form::submit('Create page', ['class' => 'btn btn-primary']) }}
          </div>                
         {{ Form::close() }}
     </div>
   </div>
@include('partials.tinymce',['editor'=>'.editor2'])
@endsection