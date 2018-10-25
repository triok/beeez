@extends('layouts.app')

@section('content')
    <div class="container-fluid organization-structure" id="main">
        <div class="row">
            <div class="col-md-12">
                <h2>@lang('organizations.title-structure')</h2>

                <div class="col-sm-2 sidebar-offcanvas category-nav" role="navigation">
                    <div id="sidebar">
                        <h2>@lang('structure.title')</h2>

                        <a href="{{ route('structure.create', $organization) }}" class="btn btn-primary btn-xs">
                            <i class="fa fa-plus"></i> @lang('structure.create')
                        </a>

                        <ul class="list-unstyled">
                            @foreach($structures as $structure)
                                <li class="{{ ($structure->id == $structures->first()->id ? 'active' : '') }}">
                                    <a data-toggle="tab" href="#panel{{ $structure->id }}">{{ $structure->name }}</a>
                                    <i class="fa fa-align-justify pull-right"></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    @foreach($structures as $structure)
                        @include('structures.partials.employees')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush