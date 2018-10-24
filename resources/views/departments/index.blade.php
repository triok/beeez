@extends('layouts.app')

@section('content')
    <div class="container-fluid organization-structure" id="main">
        <div class="row">
            <div class="col-md-12">
                <h2>@lang('organizations.title-structure') </h2>

                <div class="col-sm-2 sidebar-offcanvas category-nav" role="navigation">
                    <div id="sidebar">
                        <h2>Отделы</h2>
                        <button class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Добавить отдел</button>
                        <ul class="list-unstyled">
                            <li class="active"><a data-toggle="tab" href="#panel1">Название отдела 1</a><i class="fa fa-align-justify pull-right" ></i></li>
                            <li><a data-toggle="tab" href="#panel2">Название отдела 2</a><i class="fa fa-align-justify pull-right" ></i></li>
                        </ul>
                    </div>

                </div>
                <div class="tab-content">
                    <div id="panel1" class="tab-pane fade in active col-sm-10">
                        <div class="col-sm-3 employee">
                            <h2>Сотрудники</h2>
                            <button class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Добавить сотрудников</button>
                            <ul class="list-unstyled">
                                <li>user</li>
                                <li>manager</li>
                            </ul>
                        </div>
                        <div class="col-sm-9">
                            <h2>Проекты</h2>
                            <ul class="list-unstyled">
                                <li>Название проекта 1</li>
                                <li>Название проекта 2</li>
                                <li>Название проекта 3</li>
                                <li>Название проекта 4</li>
                            </ul>
                        </div>
                    </div>
                    <div id="panel2" class="tab-pane fade col-sm-10">
                        <div class="col-sm-3 employee">
                            <h2>Сотрудники</h2>
                            <button class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Добавить сотрудников</button>
                        </div>
                        <div class="col-sm-9">
                            <h2>Проекты</h2>
                            <li>Название проекта 4</li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush