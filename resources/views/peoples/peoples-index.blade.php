@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">

            <div class="col-xs-3">
                <div class="base-wrapper">
                    <h2>@lang('peoples.title')</h2>
                    <p>@lang('peoples.title-comment')</p>
                </div>
                <div class="base-wrapper">
                    <div class="search">
                            <input type="text" class="form-control" id="login_search" placeholder="@lang('peoples.search')">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <ul class="result"></ul>
                    </div>
                </div>
            </div>
            <div class="col-xs-9">
                <div class="base-wrapper">
                @include('peoples.partials.table', ['action' => '/api/users/search'])
                </div>
            </div>    
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush