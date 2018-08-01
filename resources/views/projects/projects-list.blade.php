@extends('layouts.app')
@section('content')
    <h2>@lang('projects.title')</h2>
    <div class="col-sm-2 pull-right">
        <form action="{{ route('new-project') }}">
            <button class="btn btn-success btn-block" type="submit"><i class="fa fa-sitemap"></i> @lang('projects.create') </button>
        </form>        
    </div>

    <table class="table table-responsive">
        <thead>
        <tr>
            <td>@lang('projects.name')</td>
            <td>@lang('projects.desc')</td>            
            <td>@lang('projects.count')</td>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>Тестовый проект 1</td>
                <td>Тестовое описание проекта</td>                
                <td>3/4</td>
             </tr>
        </tbody>
    </table>

    @lang('projects.noprojects')


@endsection

@push('scripts')
<script src="/js/custom.js"></script>
@endpush
