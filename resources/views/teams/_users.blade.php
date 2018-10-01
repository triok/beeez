<div class="row team-create">
    <div class="col-md-3">
        <div class="form-group">
            <label>@lang('teams.users')</label>

            <select id="input-users" class="form-control">
                <option selected></option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3" style="padding-left: 0">
        <div class="form-group" style="padding-top: 30px;">
            <button type="button" class="btn btn-primary btn-sm" onclick="addUser();">
                <i aria-hidden="true" class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <table class="table table-responsive" id="table-users">
            <thead>
            <tr>
                <td>@lang('teams.show_user_name')</td>
                <td>@lang('teams.show_user_position')</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            @if(isset($connections))
                @foreach($connections as $connection)
                    <tr id="user-{{ $connection->user_id }}">
                        <td>
                            <input type="hidden" name="connections[{{ $connection->user_id }}][name]"
                                   value="{{ $connection->user->name }}">
                            <a href="">{{ $connection->user->name }}</a>
                        </td>
                        <td>
                            <input name="connections[{{ $connection->user_id }}][position]" type="text"
                                   value="{{ $connection->position }}" class="form-control">
                        </td>
                        <td class="text-right">
                            <button type="button" onclick="deleteUser({{ $connection->user_id }})"
                                    class="btn btn-danger btn-sm">
                                <i aria-hidden="true" class="fa fa-close"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

<script type="application/javascript">
    function addUser() {
        var user_id = $('#input-users :selected').val();

        if (!user_id) {
            alert('Choose users!');

            return;
        }

        var user_name = $('#input-users :selected').text();

        var template = '<tr id="user-' + user_id + '"><td><input type="hidden" name="connections[' + user_id + '][name]" value="user"><a href="">' + user_name + '</a></td>';
        template += '<td><input name="connections[' + user_id + '][position]" type="text" value="" class="form-control"></td>';
        template += '<td class="text-right"><button type="button" onclick="deleteUser(' + user_id + ')" class="btn btn-danger btn-sm"><i aria-hidden="true" class="fa fa-close"></i></button></td></tr>';

        $('#table-users').find('tbody').append(template);

        $('#input-users').val(null);
    }

    function deleteUser(user_id) {
        $('#table-users').find('#user-' + user_id).remove();
    }
</script>