<div class="row structure-create">
    <div class="col-md-3">
        <div class="form-group">
            <label>@lang('structure.users')</label>

            <select id="input-users" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" >
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
                <td>@lang('structure.show_user_name')</td>
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


                        @if($organization->user_id == auth()->id())
                            <td class="text-right">
                                <button type="button" onclick="$('#user-access-{{ $connection->user_id }}').attr('class', '')"
                                        class="btn btn-default btn-sm">
                                    <i aria-hidden="true" class="fa fa-key {{ ($connection->isAccess() ? 'text-danger' : '') }}"></i>
                                </button>
                            </td>
                        @endif

                        @if($connection->user_id != $structure->user_id)
                        <td class="text-right">
                            <button type="button" onclick="deleteUser({{ $connection->user_id }})"
                                    class="btn btn-danger btn-sm">
                                <i aria-hidden="true" class="fa fa-close"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
                    @if($organization->user_id == auth()->id())
                        <tr class="hidden" id="user-access-{{ $connection->user_id }}">
                            <td colspan="4">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="connections[{{ $connection->user_id }}][can_add_user]" value="1"
                                                {{ ($connection->can_add_user ? 'checked' : '') }}>
                                        Может добавить других пользователей в этот отдел.
                                    </label>
                                    <label>
                                        <input type="checkbox" name="connections[{{ $connection->user_id }}][can_add_project]" value="1"
                                                {{ ($connection->can_add_project ? 'checked' : '') }}>
                                        Может создать проект в этом отделе.
                                    </label>
                                    <label>
                                        <input type="checkbox" name="connections[{{ $connection->user_id }}][can_add_job]" value="1"
                                                {{ ($connection->can_add_job ? 'checked' : '') }}>
                                        Может создать задания в этих проектах.
                                    </label>
                                    <label>
                                        <input type="checkbox" name="connections[{{ $connection->user_id }}][can_see_all_projects]" value="1"
                                                {{ ($connection->can_see_all_projects ? 'checked' : '') }}>
                                        Может видеть все проекты своего отдела.
                                    </label>
                                    <label>
                                        <input type="checkbox" name="connections[{{ $connection->user_id }}][can_add_user_to_project]" value="1"
                                                {{ ($connection->can_add_user_to_project ? 'checked' : '') }}>
                                        Может добавить пользователя из своего отдела в проект.
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="/plugins/bootstrap-select/bootstrap-select.min.css"/>
@endpush

@push('scripts')
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
@endpush

<script type="application/javascript">
    function addUser() {
        var user_id = $('#input-users :selected').val();

        if (!user_id) {
            alert('Choose users!');

            return;
        }

        var user_name = $('#input-users :selected').text();

        var template = '<tr id="user-' + user_id + '"><td><input type="hidden" name="connections[' + user_id + '][name]" value="user"><a href="">' + user_name + '</a></td>';
        
        template += '<td class="text-right"><button type="button" onclick="deleteUser(' + user_id + ')" class="btn btn-danger btn-sm"><i aria-hidden="true" class="fa fa-close"></i></button></td></tr>';

        $('#table-users').find('tbody').append(template);

        $('#input-users').val(null);
    }

    function deleteUser(user_id) {
        $('#table-users').find('#user-' + user_id).remove();
    }
</script>