@extends('layouts.app')

@section('content')
    <div class="container-fluid organization-structure" id="main">
        <div class="row">
            <div class="col-md-12">
                <h2>@lang('organizations.title-structure')</h2>

                <div class="col-sm-2 sidebar-offcanvas category-nav" role="navigation">
                    <div id="sidebar">
                        <h2>@lang('structure.title')</h2>

                        @if($organization->user_id == auth()->id())
                            <a href="{{ route('structure.create', $organization) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-plus"></i> @lang('structure.create')
                            </a>
                        @endif

                        <ul class="list-unstyled">
                            @foreach($structures as $structure)
                                @if($organization->user_id == auth()->id() || $structure->employees()->find(auth()->id()))
                                    <li class="{{ ($structure->id == $structures->first()->id ? 'active' : '') }}">
                                        <a data-toggle="tab" href="#panel{{ $structure->id }}">{{ $structure->name }}</a>
                                        <a href="{{ route('structure.show', [$organization, $structure]) }}">
                                            <i class="fa fa-align-justify pull-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="{{ ($structure->id == $structures->first()->id ? 'active' : '') }}">
                                        <span>{{ $structure->name }}</span>
                                        <span>
                                            <i class="fa fa-align-justify pull-right"></i>
                                        </span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    @foreach($structures as $structure)
                        @php
                            $connection = \App\Models\StructureUsers::where('structure_id', $structure->id)
                                ->where('user_id', auth()->id())
                                ->first();
                        @endphp

                        @if($organization->user_id == auth()->id() || $connection)
                            @include('structures.partials.employees')
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush