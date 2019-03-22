var _token = $('meta[name="csrf-token"]').attr('content');
var parent  = author = null;
var mess = '<div class="alert alert-info fade in" id="reply-container">\n' +
    '<a href="#" class="close" data-dismiss="alert">&times;</a>\n' +
    'Reply to comment <strong class="reply-user"></strong>\n' +
    '</div>';

window.onload = function(){
    $(document).on('click', '#separate-link', function () {
        $('#separate-link').addClass('hide');

        //$('#task2').toggleClass('hide');
        $('#task-add').toggleClass('hide');
        $('#task-list').toggleClass('hide');
        //$('#task-list-nav').toggleClass('hide');
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
    $(document).on('click', '.btn-decline', declineJob);

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

function confirmDelete(form) {
    if (confirm('Are you sure?')) {
        form.submit();
    }

    return false;
}

$(document).ready(function () {
    $('.input-category-name').on('click', function () {
        $('#modal-task-id').val($(this).attr('name'));

        $('#modal-categories').modal({ backdrop: 'static', keyboard: false, 'show': true });
    });

    $("#modal-categories").on("show", function () {
        $("body").addClass("modal-open");
    }).on("hidden", function () {
        $("body").removeClass("modal-open")
    });

    $("#modal-categories").on('hidden.bs.modal', function () {
        $('html, body').animate({
            scrollTop: $(".input-category-name").offset().top - 300
        }, 0);
    });
    $('.carets').on('click', function () {
        $(this).toggleClass("rotate-caret");
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

function addSubTask(task_id) {
    console.log(task_id);

    var load = $('<div>');

    load.load('/job/subtask?task_id=' + task_id, function (result) {
        $('.tab-content .tab-pane').after(result);

        $('.editor1').summernote();

        tinymce.init({
            selector: '#sub-instruction-' + task_id,
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

    $('#reply-container .reply-user').text(author);

    var message = "<В ответ на комментарий пользователя: " + author + ">\n";

    $('#body').text(message);

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
function declineJob() {
    var id = parseInt($(this).attr('data-id'));
    var container = $('#declineJobForm');

    container.find('form').attr('action', '/jobs/'+ id +'/decline');
    container.modal({ backdrop: 'static', keyboard: false, 'show': true });
}
function showSubCategories(id) {
    $('.subcategories').css('display', 'none');

    $('#subcategories-' + id).css('display', 'block');
}

function setCategory(id, name) {
    var field_name = $('#modal-task-id').val();

    if(field_name == 'category_name') {
        $('input[name="categories[]"]').val(id);
        $('input[name="category_name"]').val(name);
    } else {
        var field_id = field_name.replace('category_name', 'categories[]');

        $('input[name="' + field_id + '"]').val(id);
        $('input[name="' + field_name + '"]').val(name);
    }

    $('#modal-categories').modal('hide');
}