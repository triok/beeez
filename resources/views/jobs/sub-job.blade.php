@php
    $sub = isset($sub_id) ? $sub_id : 1;
@endphp

<div class="sub-item">
<table class="table" id="sub-{{$sub}}-task">

    <caption><h4 class="popover-title cursor-pointer" id="sub-{{$sub}}-title">Task # {{$sub}}<span id="sub-{{$sub}}-num"></span></h4></caption>

    <tbody class="{{$sub == 1 ? '' : 'hide'}}">
    <tr class="form-group">
        <td><label for="sub-{{$sub}}-name">Name:</label></td>
        <td><input type="text" class="form-control" id="sub-{{$sub}}-name" name="sub-{{$sub}}-name"></td>
    </tr>
    <tr class="form-group">
        <td> <label for="sub-{{$sub}}-desription">Description:</label></td>
        <td><textarea class="form-control" rows="3" id="sub-{{$sub}}-desription" name="sub-{{$sub}}-desription"></textarea></td>
    </tr>
    <tr class="form-group">
        <td> <label for="sub-{{$sub}}-instruction">Instruction:</label></td>
        <td><textarea class="form-control" rows="3" id="sub-instruction-{{$sub}}" name="sub-{{$sub}}-instruction"></textarea></td>
    </tr>
    <tr class="form-group">
        <td> <label for="sub-{{$sub}}-access">Access:</label></td>
        <td><input type="text" id="sub-{{$sub}}-access" name="sub-{{$sub}}-access" class="form-control"></td>
    </tr>
    <tr class="form-group">
        <td> <label for="sub-{{$sub}}-price">Price:</label></td>
        <td>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" name="sub-{{$sub}}-price" id="sub-{{$sub}}-price" class="form-control">
            </div>
        </td>
    </tr>
    <tr class="form-group">
        <td> <label for="sub-{{$sub}}-difficulty_level">Difficulty level:</label></td>
        <td>
            <select class="form-control" id="sub-{{$sub}}-difficulty_level" name="sub-{{$sub}}-difficulty_level">
                @foreach($_difficultyLevels as $key => $level)
                    <option value="{{$key}}">{{$level}}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr class="form-group">
        <td><label for="sub-{{$sub}}-end_date">End date:</label></td>
        <td><input type="datetime-local" id="sub-{{$sub}}-end_date" name="sub-{{$sub}}-end_date" class="form-control"></td>
    </tr>
    <tr class="form-group">
        <td><label for="sub-{{$sub}}-time_for_work">Time for work</label></td>
        <td>
            <select name="sub-{{$sub}}-time_for_work" id="sub-{{$sub}}-time_for_work" class="form-control">
                <option value="1" selected="selected">1 hour</option>
                <option value="2">2 hours</option>
                <option value="3">3 hours</option>
            </select>
        </td>
    </tr>
    <tr class="form-group">
        <td></td>
        <td>
            <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="sub-{{$sub}}-user">
                <option selected value="">For anyone</option>

                @foreach($usernames as $key => $username)
                    <option value="{{$key}}">{{$username}}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr class="form-group">
        <td><label for="sub-{{$sub}}-skills[]">Skills:</label></td>
        <td>
            @foreach($_skills as $skill)
                <label class="checkbox-inline"><input type="checkbox" name="sub-{{$sub}}-skills[]"  id="sub-skill-{{$skill->id}}" value="{{$skill->id}}">{{ucwords($skill->name)}}</label>
            @endforeach
        </td>
    </tr>
    <tr class="form-group">
        <td><label for="sub-{{$sub}}-categories[]">Categories:</label></td>
        <td>
            @foreach($_categories as $category)
                <label class="checkbox-inline"><input type="checkbox" name="sub-{{$sub}}-categories[]"  id="sub-category-{{$category->id}}" value="{{$category->id}}">{{ucwords($category->name)}}</label>
            @endforeach
        </td>
    </tr>
    <tr class="form-group">
        <td> <label for="sub-{{$sub}}-tag">Choose CMS:</label></td>
        <td>
            <select name="sub-{{$sub}}-tag" id="sub-{{$sub}}-tag" class="form-control">
                <option value="" selected>I do not use CMS</option>
                @foreach(config('tags.tags') as $tag)
                    <option value="{{$tag['value']}}" {{isset($job) && isset($job->tag) && $job->tag->value == $tag['value'] ? 'selected' : ''}}>{{$tag['title']}}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr class="form-group">
        <td> <label for="sub-{{$sub}}-files">Files:</label></td>
        <td><input type="file" multiple name="sub-{{$sub}}-files[]" id="sub-{{$sub}}-files"></td>
    </tr>
    </tbody>
</table>
</div>