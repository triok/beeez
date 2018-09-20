<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-name">Group name</label>
            {!! Form::text('subject', old('subject', isset($thread) ? $thread->subject : ''), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-name']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-avatar">Avatar</label><br>

            @if(isset($thread) && $thread->avatar())
                <img src="{{ $thread->avatar() }}"
                     class="img-thumbnail"
                     alt="{{ $thread->name }}"
                     title="{{ $thread->name }}"
                     style="width: 100px; height: 100px;margin-bottom: 5px;">
            @endif

            <input type="file" name="avatar" id="input-avatar">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="input-description">Description</label>
            {!! Form::textarea('description', old('description', isset($thread) ? $thread->description : ''), ['class' => 'editor1', 'id' => 'input-description']) !!}
        </div>
    </div>
</div>