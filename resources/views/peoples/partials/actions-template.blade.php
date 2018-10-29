<div id="actions-template" class="hidden">
    {!! Form::open(['url' => '/peoples/#id/favorite', 'method'=>'post', 'id' => 'form-favorite-#id', 'class' => 'hide']) !!}
    <button type="submit" class="btn btn-sm btn-default btn-round" title="@lang('projects.favorite_add')">
        <i class="fa fa-star-o fa-fw"></i>
    </button>
    {!! Form::close() !!}

    {!! Form::open(['url' => '/peoples/#id/unfavorite', 'method'=>'post', 'id' => 'form-unfavorite-#id', 'class' => 'hide']) !!}
    <button type="submit" class="btn btn-sm btn-default btn-round" title="@lang('projects.favorite_del')">
        <i class="fa fa-star fa-orange fa-fw"></i>
    </button>
    {!! Form::close() !!}

    {!! Form::open(['url' => '/threads', 'method' => 'post', 'id' => 'form-threads-#id', 'class' => 'inline']) !!}
    <input type="hidden" name="user_id" value="#id">
    <button class="btn btn-primary btn-sm btn-round" title="@lang('peoples.message')">
        <i class="fa fa-envelope fa-fw" aria-hidden="true"></i>
    </button>
    {!! Form::close() !!}
</div>