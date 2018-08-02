@extends('layouts.app')
@section('content')
    <h2>Project name</h2>
                                
                               
    @permission('create-jobs')                            
    <form action="/jobs/create">                            
	<button type="submit" class="btn btn-success" style="margin-top: 10px;">Post new job</button>
	</form>
	@endpermission
    <div class="col-xs-12">
    	<table class="table table-sm table-hover">
    		<thead>
    			<tr>
    				<td>@lang('projects.job-name')</td>
    				<td>@lang('projects.deadline')</td>
    				<td>@lang('projects.executor')</td>
    				<td>@lang('projects.price')</td>
    				<td>@lang('projects.published')</td>
    			</tr>
    		</thead>
    		<tbody>
    			<tr>
    				<td>Задание 1</td>
    				<td>04 Августа 20:30</td>
    				<td>нет</td>
    				<td>$15</td>
    				<td>Не опубликовано</td>
    			</tr>
    			<tr>
    				<td>Задание 2</td>
    				<td>02 Августа 14:00</td>
    				<td>User</td>
    				<td>$10</td>
    				<td>01.08 14:54</td>
    			</tr>
    			<tr>
    				<td>Задание 3</td>
    				<td>05 Августа 12:00</td>
    				<td>нет</td>
    				<td>$20</td>
    				<td>Не опубликовано</td>
    			</tr>    			    			    			
    		</tbody>
    	</table>
    </div>
    <div class="pull-right">
    	<p><span>@lang('projects.jobs-total')</span> 3 <span>@lang('projects.summ-total')</span> $45</p>
    	<p><span>@lang('projects.jobs-complete')</span> 0</p>
    </div>

@endsection

@push('scripts')
<script src="/js/custom.js"></script>
@endpush
