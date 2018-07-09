{!! Form::open(['url'=>'update-role-permissions','class'=>'permissions']) !!}
<a href="#" id="invert_selection">Select All</a><br/>
@foreach($rolePerms as $rp)
    <input type="checkbox" @if($rp['selected'] == true) checked @endif name="permissions[]"
           value="{{$rp['level']}}"> {{ucwords($rp['level'])}} <br/>
@endforeach
<br/>
<button class="btn btn-default btn-sm">Update</button>
{!! Form::close() !!}