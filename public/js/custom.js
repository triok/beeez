var _token = $('meta[name="csrf-token"]').attr('content');
// var count = $('.sub-tasks').find('input[name="sub-count-tasks"]').val();

window.onload = function(){

    $(document).on('click', '#separate-link', function () {
        $('.sub-tasks').toggleClass('hide');
    });

    $(document).on('click', '.sub-tasks .table h4', function () {
        $(this).closest('.table').find('tbody').toggleClass('hide');
    });

    $(document).on('click', '#taskAdd', addSubTask);

};

function addSubTask() {
    var load = $('<div>');
    var subTasks = $('.sub-tasks');
    // $('.sub-tasks').find('input[name="sub-count-tasks"]').val(++count);

    if(subTasks.find('.sub-item').length == 10) return false;

    load.load('/job/subtask?sub_id=' + ++subTasks.find('.sub-item').length, function (result) {
        subTasks.find('.sub-item').last().after(result);
    });
}


