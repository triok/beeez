@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">

            <div class="col-xs-3">
                <div class="base-wrapper">
                    <h2>@lang('peoples.title')</h2>
                    <p>@lang('peoples.title-comment')</p>
                </div>
                <div class="base-wrapper">
                    <h2>@lang('peoples.filter')</h2>
                    <div class="search">
                        <input type="text" class="form-control" id="login_search" placeholder="@lang('peoples.search')">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <ul class="result"></ul>
                    </div>
                    <div>
                        <h2>@lang('peoples.speciality')</h2>
                        <ul class="list-unstyled speciality" id="people-specialization-filter">
                        @foreach($specialities as $key => $value)
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           value="{{ $key }}" 
                                           id="input-speciality-{{ $key }}">
                                    <label class="form-check-label" for="input-speciality-{{ $key }}">
                                        {{ $value }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    <div>
                        <h2>@lang('peoples.skills')</h2>
                        <ul class="list-unstyled speciality">
                        @foreach($skills as $skill)
                            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="{{ $skill }}"><label class="form-check-label" for="defaultCheck2">{{ $skill }}</label></div></li>
                        @endforeach
                        </ul>
                    </div>
                    <div>
                        <h2>@lang('peoples.country')</h2>
                        <select class="form-control">
                            @foreach($countries as $country)
                            <option>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div>
                        <h2>@lang('peoples.city')</h2>
                        <select class="form-control">
                            @foreach($cities as $city)
                            <option>{{ $city }}</option>
                            @endforeach
                        </select>                    
                    </div>
                    <div>
                        <h2>@lang('peoples.confirmation')</h2>
                        <ul class="list-unstyled speciality">
                            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="confirmed"><label class="form-check-label" for="confirmed"> @lang('peoples.confirmed')</label></div></li>
                        </ul>

                    </div>                                                           
                </div>
                <div class="base-wrapper peoples-registered">
                    <p class="number">{{ (count($users) + 1) }}</p>
                    <p class="title">@lang('peoples.count')</p>
                    <p class="comment">@lang('peoples.count-comment')</p>
                    <i class="fa fa-user-plus" aria-hidden="true"></i>                
               </div>               
            </div>
            <div class="col-xs-9">
                <div class="base-wrapper">
                @include('peoples.partials.table', ['action' => '/api/users/search?all=true'])
                </div>
            </div>    
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush