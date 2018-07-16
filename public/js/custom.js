var _token = $('meta[name="csrf-token"]').attr('content');
var parent  = author = null;
var mess = '<div class="alert alert-info fade in" id="reply-container">\n' +
    '<a href="#" class="close" data-dismiss="alert">&times;</a>\n' +
    'Reply to comment <strong class="reply-user"></strong>\n' +
    '</div>';

window.onload = function(){

    var sub = null;

    $(document).on('click', '#separate-link', function () {
        sub = $('.sub-tasks');
        if(sub.hasClass('hide')) {sub.toggleClass('hide');}

        $('html, body').animate({
            scrollTop: sub.offset().top - 50
        });
    });

    $(document).on('click', '.sub-tasks .table h4', function () {
        $(this).closest('.table').find('tbody').toggleClass('hide');
    });


    $(document).on('click', '#taskAdd', addSubTask);
    $(document).on('click', '.comment-reply .reply', addComment);
    $(document).on('click', '.form-container .alert .close', function () {
        $('#parent').val('');
    });

};

function addSubTask() {
    var load = $('<div>');
    var subTasks = $('.sub-tasks');
    var lenght = ++subTasks.find('.sub-item').length;

    if(subTasks.find('.sub-item').length == 10) return false;

    load.load('/job/subtask?sub_id=' + lenght, function (result) {
        subTasks.find('.sub-item').last().after(result);
        $('.editor1').summernote();
        tinymce.init({
            selector: '#sub-instruction-' + lenght,
            height: 400,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools',
                'autoresize'
            ],
            toolbar1: 'insertfile undo redo | styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media',
            image_advtab: true,
            content_css: [
                '/plugins/tinymce/codepen.min.css'
            ]
        });

        $('.selectpicker').selectpicker('refresh');

    });
}
function addComment()
{
    parent = $(this).closest('.media');
    $('#parent').val(parent.attr('data-id'));
    author = parent.find('.author').first().text();

    if(!$('#reply-container').length) {
        $('.form-container h4').after(mess);
    }

    $('#reply-container .reply-user').text(author)

    document.getElementById("body").focus();

}


