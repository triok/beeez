<div class="row structure-create">
    <div class="col-md-6">
        <div class="form-group">
            <label>Фолловеры</label>

            <select id="input-followers" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" >
                <option selected></option>
                @if($allFollowers)
                    @foreach($allFollowers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3" style="padding-left: 0">
        <div class="form-group" style="padding-top: 30px;">
            <button type="button" class="btn btn-primary btn-sm" onclick="addFollower();">
                <i aria-hidden="true" class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <table class="table table-responsive" id="table-followers">
            <tbody>
            @if(isset($followers))
                @foreach($followers as $follower)
                    <tr id="follower-{{ $follower->user_id }}">
                        <td>
                            <input type="hidden" name="followers[{{ $follower->user_id }}][name]"
                                   value="{{ $follower->user->name }}">
                            <a href="">{{ $follower->user->name }}</a>
                        </td>

                        @if($follower->user_id != $structure->user_id)
                        <td class="text-right">
                            <button type="button" onclick="deleteFollower({{ $follower->user_id }})"
                                    class="btn btn-danger btn-sm">
                                <i aria-hidden="true" class="fa fa-close"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
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
    function addFollower() {
        var follower_id = $('#input-followers :selected').val();

        if (!follower_id) {
            alert('Choose followers!');

            return;
        }

        var follower_name = $('#input-followers :selected').text();

        var template = '<tr id="follower-' + follower_id + '"><td><input type="hidden" name="followers[' + follower_id + '][name]" value="follower"><a href="">' + follower_name + '</a></td>';
        template += '<td class="text-right"><button type="button" onclick="deleteFollower(' + follower_id + ')" class="btn btn-danger btn-sm"><i aria-hidden="true" class="fa fa-close"></i></button></td></tr>';

        $('#table-followers').find('tbody').append(template);

        $('#input-followers').val(null);
    }

    function deleteFollower(follower_id) {
        $('#table-followers').find('#follower-' + follower_id).remove();
    }
</script>