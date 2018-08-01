@extends('layouts.app')
@section('content')
    <h2>@lang('projects.title-create')</h2>
    <div class="col-sm-6">
    <form action="{{route('comments.store')}}" method="post">
        {{csrf_field()}}
        <input name="title" class="form-control" placeholder="Project title">
        <br>
        <textarea name="description" rows="3" class="form-control" placeholder="Project description"></textarea>
        <button type="submit" class="btn btn-success" style="margin-top: 10px;">Create</button>
    </form>

@endsection

@push('scripts')
<script src="/js/custom.js"></script>
@endpush
