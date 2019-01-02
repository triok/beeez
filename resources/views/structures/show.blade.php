@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-12 team-show">
                <div style="margin-bottom: 5px;">
                    <a href="{{ route('structure.index', $organization) }}">
                        <span>
                            <i class="fa fa-arrow-left"></i>
                            @lang('teams.back_to_list')
                        </span>
                    </a>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="pull-right">
                            @php
                                $connection = \App\Models\StructureUsers::where('structure_id', $structure->id)
                                    ->where('user_id', auth()->id())
                                    ->first();
                            @endphp

                            @if(auth()->user()->isOrganizationFullAccess($organization) || ($connection && $connection->can_add_project))
                                <a href="{{ route('projects.create') }}?structure_id={{ $structure->id }}"
                                   class="btn btn-primary btn-block">

                                    <i class="fa fa-sitemap"></i> @lang('projects.create')
                                </a>
                            @endif
                        </div>

                        @include('structures.partials.projects')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .tab-content {
            margin-top: 20px;
        }

        .table-responsive tr td:first-child {
            width: 20px;
        }

        .table-responsive tr td:last-child {
            min-width: 200px;
        }

        .table-responsive form {
            display: inline-block;
        }
    </style>
@endpush