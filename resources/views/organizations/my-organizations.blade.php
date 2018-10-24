@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
   <div class="row">
      <div class="col-md-12">
         <h2>@lang('organizations.title-my')</h2>

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
               <th>Структура</th>
            </tr>
            </thead>
            <tbody>
            @foreach($organizations as $organization)
               <tr>
                  <td>
                     <a href="{{ route('organizations.show', $organization) }}">{{$organization->name}}</a>

                     @if(auth()->id() == $organization->user_id)
                        <i class="fa fa-star"></i>

                        @if($organization->status == 'moderation')
                           (<span class="text-warning">на модерации</span>)
                        @endif

                        @if($organization->status == 'rejected')
                           (<span class="text-danger">модерация провалена</span>)
                        @endif
                     @endif
                  </td>
                  <td>
                     <a href="{{ route('peoples.show', $organization->user) }}">{{$organization->user->name}}</a>
                  </td>
                  <td>
                     @if($organization->status == 'approved')
                     <a href="{{ route('structure.index', $organization) }}">Войти</a>
                     @endif
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