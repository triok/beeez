<h2 style="margin: 0 0 0 20px;">@lang('account.examples')</h2>

@foreach(auth()->user()->portfolio as $info)
<div class="row">
    <div class="col-md-12">
        <div class="base-wrapper">
            <form action="/account/portfolio/{{$info->id}}" method="post" enctype="multipart/form-data" class="form-delete pull-right">
                <input type="hidden" name="_method" value="delete" />
                {{csrf_field()}}

                <button class="btn btn-xs btn-danger">
                    <i class="fa fa-trash"></i>
                </button>
            </form>

            <h2>{{ $info->name }}</h2>
            <p>{!! $info->description !!}</p>
            @if($files = $info->files)
                <ul>
                    @foreach($files as $file)
                    <li><a target="_blank" href="{{Storage::url($file->file)}}">{{ $file->original_name }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endforeach

<form action="/account/portfolio" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-12">
            <div class="base-wrapper">
                <div class="form-group">
                    <label>Название</label>
                    {!! Form::text('name','',['class'=>'form-control','required'=>'required']) !!}
                </div>

                <div class="form-group">
                    <label>Описание</label>
                    <div class="job-description">
                        {!! Form::textarea('description', '',['class'=>'editor1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label>Файлы</label>
                    <div class="form-group">
                        <input type="file" name="portfolio[]">
                    </div>
                    <div class="form-group">
                        <input type="file" name="portfolio[]">
                    </div>
                    <div class="form-group">
                        <input type="file" name="portfolio[]">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10" style="margin-left: 20px;">
            <button class="btn btn-default">@lang('account.save')</button>
        </div>
    </div>
</form>

@include('partials.summer',['editor'=>'.editor1'])
