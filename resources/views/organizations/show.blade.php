@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="row">
        <div class="col-md-12">
            <hr>

            <div style="margin-bottom: 5px;">
                <a href="{{ route('organizations.index') }}">
                    <span><i class="fa fa-arrow-left"></i> @lang('organizations.back_to_list')</span>
                </a>

                @can('update', $organization)
                    <a href="{{ route('organizations.edit', $organization) }}" class="btn btn-default btn-xs pull-right">
                        <i class="fa fa-pencil"></i> @lang('organizations.edit')
                    </a>
                @endif

                @if(auth()->user()->email == config('organization.admin') && $organization->status == 'moderation')
                    {!! Form::open(['url' => route('organizations.approve', $organization), 'method'=>'post', 'class' => 'pull-right', 'style' => 'display:inline-block;margin-right: 5px;']) !!}
                    <button type="submit" class="btn btn-xs btn-success">Approve</button>
                    {!! Form::close() !!}

                    {!! Form::open(['url' => route('organizations.reject', $organization), 'method'=>'post', 'class' => 'pull-right', 'style' => 'display:inline-block;margin-right: 5px;']) !!}
                    <button type="submit" class="btn btn-xs btn-danger">Reject</button>
                    {!! Form::close() !!}
                @endif
            </div>

            @can('update', $organization)
                @include('organizations.partials.owner-info')
            @else
                @include('organizations.partials.guest-info')
            @endcan
        </div>
    </div>
</div>    
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush