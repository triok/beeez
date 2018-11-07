<div id="vacancy-actions-template" class="hidden">
    {!! Form::open(['url' => '/vacancies/#slug/favorite', 'method'=>'post', 'id' => 'vacancy-form-favorite-#id', 'class' => 'hide']) !!}
    <button type="submit" class="btn btn-sm btn-default" title="@lang('projects.favorite_add')">
        <i class="fa fa-star-o"></i>
    </button>
    {!! Form::close() !!}

    {!! Form::open(['url' => '/vacancies/#slug/unfavorite', 'method'=>'post', 'id' => 'vacancy-form-unfavorite-#id', 'class' => 'hide']) !!}
    <button type="submit" class="btn btn-sm btn-default" title="@lang('projects.favorite_del')">
        <i class="fa fa-star fa-orange"></i>
    </button>
    {!! Form::close() !!}

    <button onclick="location.href='/vacancies/#slug/cvs/create'" id="cv-link-#id" disabled class="btn btn-primary btn-sm" style="color: #fff">
        @lang('vacancies.button_add_cv')
    </button>
</div>