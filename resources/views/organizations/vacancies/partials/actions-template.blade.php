<div id="vacancy-actions-template" class="hidden">
    {!! Form::open(['url' => '#route/vacancies/#id/publish', 'method'=>'patch', 'id' => 'form-publish-#id', 'class' => 'hide']) !!}
    <button type="submit" class="btn btn-sm btn-default" title="@lang('vacancies.button_publish')">
        <i class="fa fa-check"></i>
    </button>
    {!! Form::close() !!}

    <a href="#route/vacancies/#id/edit" class="btn btn-sm btn-success" title="@lang('vacancies.button_edit')">
        <i class="fa fa-pencil" style="color:#fff;"></i>
    </a>

    {!! Form::open(['url' => '#route/vacancies/#id', 'method'=>'delete', 'class' => 'inline']) !!}
    <button type="button" onclick="confirmDelete(this.form)" class="btn btn-sm btn-danger" title="@lang('vacancies.button_delete')">
        <i class="fa fa-trash"></i>
    </button>
    {!! Form::close() !!}
</div>