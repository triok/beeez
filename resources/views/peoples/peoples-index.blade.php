@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-12">
                <h2>@lang('peoples.title')</h2>

                <div class="row">
                    <div class="col-md-3 search">
                        <input type="text" class="form-control" id="login_search" placeholder="@lang('peoples.search')">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <ul class="result"></ul>
                    </div>
                </div>

                @include('peoples.partials.table', ['action' => '/api/users/search'])
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush