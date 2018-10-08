@extends('layouts.app')
@section('content')
<div class="container-fluid" id="main">
   <div class="row">
      <div class="col-md-12 teams">
         <h2>@lang('teams.title')</h2>
         <div class="row">
            <div class="col-md-2 search">
               <input type="text" class="form-control" id="team_search" placeholder="@lang('teams.search')"><i class="fa fa-search" aria-hidden="true"></i>
               <ul class="result"></ul>
            </div>
             <div class="col-md-2 search">
                 <select id="team-type-filter" class="form-control" style="border: none; border-bottom: 1px solid #ccd0d2;">
                     <option value="">тип команды ...</option>
                 </select>
             </div>
            <div class="col-md-8 search-button">
               <a href="{{ route('teams.create') }}" class="btn btn-primary btn-md pull-right">
                  <i class="fa fa-plus-circle"></i> @lang('teams.create_team')
               </a>
            </div>
         </div>
         <table class="table table-striped table-responsive table-full-width table-search" id="teams-table">
            <thead>
            <tr>
               <th>@lang('teams.team')</th>
               <th>@lang('teams.owner')</th>
               <th>@lang('teams.show_team_type')</th>
               <th>@lang('teams.show_date')</th>
            </tr>
            </thead>
            <tbody></tbody>
         </table>
      </div>
   </div>
</div>
@endsection



@push('styles')
   <link href="/css/custom.css" rel="stylesheet">
@endpush

@push('scripts')
   <script>
       $(document).ready(function () {
           var table = $('#teams-table').DataTable({
               bFilter: false,
               bInfo: false,
               "lengthChange": false,

               "pageLength": 20,

               "fnDrawCallback": function(oSettings) {
                   if ($('#teams-table tr').length < 21) {
                       $('.dataTables_paginate').hide();
                   }
               },

               "initComplete": function () {
                   this.api().columns().every(function () {
                       var column = this;

                       if(column[0][0] != 2) return;

                       column.data().unique().sort().each( function ( d, j ) {
                           $('#team-type-filter').append( '<option value="'+d+'">'+d+'</option>' )
                       });
                   });
               },

               "ajax": {
                   "url": "api/v1/teams",
                   "dataSrc": ""
               },

               "columns": [
                   {
                       "data": "name",
                       "render": function (data, type, row, meta) {
                           if (type === 'display') {
                               return '<a href="teams/' + row.slug + '">' + data + '</a> <i class="fa fa-star"></i>';
                           }

                           return data;
                       }
                   },
                   {
                       "data": "user.name",
                       "render": function (data, type, row, meta) {
                           if (type === 'display') {
                               return '<a href="users/' + row.user.id + '">' + data + '</a>';
                           }

                           return data;
                       }
                   },
                   {"data": "type.name"},
                   {
                       "data": "created_at",
                       "render": function (data, type, row, meta) {
                           if (type === 'display') {
                               return '<span class="date-short">' + moment(data, "YYYY-MM-DD mm:ss").format("ll") + '</span>';
                           }

                           return data;
                       }
                   },
               ]
           });

           $('#team-type-filter').on( 'change', function () {
               table.columns(2).search( this.value ).draw();
           });
       });
   </script>
@endpush