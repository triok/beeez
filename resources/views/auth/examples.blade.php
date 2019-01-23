<div class="row">
    <div class="col-md-12">
        <div class="base-wrapper">
            <p>
                <h2>@lang('account.examples')</h2>
                <br>
                <p>
	                <label>Название</label>
	                {!! Form::text('name','',['class'=>'form-control','required'=>'required']) !!}
            	</p>
                <p>
	                <label>Описание</label>
	                     <div class="job-description">
                           {!! Form::textarea('description', '',['class'=>'editor1']) !!}
                        </div>

            	</p>            	
            </p>
        </div>            
    </div>
</div>
@include('partials.summer',['editor'=>'.editor1'])
