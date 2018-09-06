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
    $(document).on('click', '.table .rm-sb', removeSubTask);
    $(document).on('click', '.form-container .alert .close', function () {
        $('#parent').val('');
    });

    $(document).on('click', '.delete', deleteTask);
    $(document).on('click', '.delete', deleteTask);
    $(document).on('click', '.btn-review', completeJob);
    $(document).on('click', '.btn-rating', ratingJob);

    $('#form-complete').submit(function(e) {
        e.preventDefault();

        if (confirm('Are you sure?')) {
            $(this).unbind('submit').submit();

        }
        return false;


    });

    $('.form-delete').submit(function(e) {
        e.preventDefault();

        if (confirm('Are you sure?')) {
            $(this).unbind('submit').submit();

        }

        return false;
    });
};

$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#side').toggleClass('active');

        $("i", this).toggleClass("fa-arrow-left fa-arrow-right");
    });

});

function removeSubTask() {
    var subTasks = $('.sub-tasks').find('.sub-item');

    if (subTasks.length <= 1) return false;

    if(confirm("Are you sure you want to delete?")) {
        $(this).closest('.sub-item').remove();
    }
}
function deleteTask() {
    var id = $(this).attr('data-id');
    $.ajax({
        url: '/jobs/' + id,
        type: 'DELETE',
        success: function success(response) {
            var msg = JSON.parse(response);
            notice(msg.message, msg.status);
        },
        error: function error(_error10) {
            notice('Error! Please try again', 'error');
        }
    });
}
function showMenu() {
$('#showHideContent').click(function(){
  $("#content").css("display", "none;");
});
}
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
function addComment() {
    parent = $(this).closest('.media');
    $('#parent').val(parent.attr('data-id'));
    author = parent.find('.author').first().text();

    if(!$('#reply-container').length) {
        $('.form-container h4').after(mess);
    }

    $('#reply-container .reply-user').text(author)

    document.getElementById("body").focus();

}
function completeJob() {
    var id = parseInt($(this).attr('data-id'));
    var container = $('#completeJobForm');

    container.find('form').attr('action', '/jobs/'+ id +'/review');
    container.modal({ backdrop: 'static', keyboard: false, 'show': true });

}
function ratingJob() {
    var id = parseInt($(this).attr('data-id'));
    var container = $('#ratingJobForm');

    container.find('form').attr('action', '/jobs/'+ id +'/rating');
    container.modal({ backdrop: 'static', keyboard: false, 'show': true });
}

