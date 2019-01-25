@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-8 col-xs-10">
                <div class="base-wrapper">
                    <v-tasks></v-tasks>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="/js/datepicker.min.js"></script>
@endpush

