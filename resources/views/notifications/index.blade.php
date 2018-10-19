@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="notifications">
        <h2>Уведомления</h2>

        <hr>

        <div class="row">
            @foreach($notifications as $notification)
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="display: inline-block;">
                                {{ $notification['title'] }}
                            </h3>

                            <div class="pull-right">
                                <span class="date-short">
                                    {{ $notification['date'] }}
                                </span>
                            </div>
                        </div>

                        <div class="panel-body">
                            {{ $notification['message'] }}
                        </div>

                        @if(!$notification['is_archived'])
                            <div class="panel-footer">
                                @foreach($notification['actions'] as $action)
                                    {!! Form::open(['url' => $action['route'], 'method'=>'post', 'style' => 'display: inline-block']) !!}
                                    <input type="hidden" name="id" value="{{ $notification['id'] }}">

                                    <button type="submit" class="btn btn-sm {{ $action['class'] }}">
                                        {{ $action['title'] }}
                                    </button>
                                    {!! Form::close() !!}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
