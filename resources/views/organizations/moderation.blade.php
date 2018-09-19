@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
   <div class="row">
      <div class="col-md-12">
         <h2>@lang('organizations.title')</h2>

         <div class="row">


            <div class="col-md-4">
               <input type="text" class="form-control pull-right" id="organization_search" placeholder="@lang('organizations.search')">
               <ul class="result"></ul>
            </div>
            <div class="col-md-8">
               <a href="{{ route('organizations.create') }}" class="btn btn-default btn-md pull-right">
                  <i class="fa fa-plus-circle"></i> @lang('organizations.create_organization')
               </a>
            </div>
         </div>
         <table class="table table-striped table-responsive table-full-width">
            <thead>
            <tr>
               <th>@lang('organizations.organization')</th>
               <th>@lang('organizations.owner')</th>
               <th> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($organizations as $organization)
               <tr>
                  <td>
                     <a href="{{ route('organizations.show', $organization) }}">{{$organization->name}}</a>

                     @if($organization->status == 'moderation')
                        (<span class="text-warning">на модерации</span>)
                     @endif

                     @if($organization->status == 'rejected')
                        (<span class="text-danger">модерация провалена</span>)
                     @endif
                  </td>
                  <td>
                     <a href="{{ route('peoples.show', $organization->user) }}">{{$organization->user->name}}</a>
                  </td>
                  <td class="text-right">
                     {!! Form::open(['url' => route('organizations.reject', $organization), 'method'=>'patch', 'style' => 'display:inline-block']) !!}
                     <button type="submit" class="btn btn-xs btn-danger">Reject</button>
                     {!! Form::close() !!}

                     {!! Form::open(['url' => route('organizations.approve', $organization), 'method'=>'patch', 'style' => 'display:inline-block']) !!}
                        <button type="submit" class="btn btn-xs btn-success">Approve</button>
                     {!! Form::close() !!}
                  </td>
               </tr>
            @endforeach

            </tbody>
         </table>
         {!! $organizations->links() !!}
      </div>
   </div>
</div>
@endsection