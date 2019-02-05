
@push('styles')
<link rel="stylesheet" type="text/css" href="/plugins/tokeninput/css/token-input.css"/>
@endpush
@push('scripts')
<script type="text/javascript" src="/plugins/tokeninput/js/jquery.tokeninput.js"></script>

<script type="text/javascript">
    var tokenElement = $('meta[name="csrf-token"]');
    var _token = tokenElement.attr('content');

    function deleteUserSkill(item) {
        $.ajax({
            url: '/delete-my-skill',
            data: { _token: _token, skill_id: item.id },
            type: 'POST',
            success: function success(response) {
                notice('Deleted!', 'success');
            },
            error: function error(_error7) {
                notice('Error!', 'error');
            }
        });
    }

    $(document).ready(function () {
        var tokenField = $("#{{$elem}}").tokenInput("{{$path}}",{
            preventDuplicates: true,
            minChars: 1,
            tokenLimit: 10,
            onDelete: function (item) {
                deleteUserSkill(item);
            }
        });

        @foreach($elements as $element)
        tokenField.tokenInput("add", {id: '{{$element->id}}', name: '{{$element->name}}'});
        @endforeach
    });
</script>
@endpush