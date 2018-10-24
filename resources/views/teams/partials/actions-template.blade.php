<div id="team-actions-template" class="hidden">
    {!! Form::open(['url' => '/teams/#slug/favorite', 'method'=>'post', 'id' => 'team-form-favorite-#id', 'class' => 'hide']) !!}
    <button type="submit" class="btn btn-sm btn-default" title="@lang('projects.favorite_add')">
        <i class="fa fa-star-o"></i>
    </button>
    {!! Form::close() !!}

    {!! Form::open(['url' => '/teams/#slug/unfavorite', 'method'=>'post', 'id' => 'team-form-unfavorite-#id', 'class' => 'hide']) !!}
    <button type="submit" class="btn btn-sm btn-default" title="@lang('projects.favorite_del')">
        <i class="fa fa-star fa-orange"></i>
    </button>
    {!! Form::close() !!}
</div>