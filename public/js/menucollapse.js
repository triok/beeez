$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#side').toggleClass('active');
        $("i", this).toggleClass("fa-arrow-left fa-arrow-right");
        if(Cookies.get('hide-sidebar') === undefined) {
            Cookies.set('hide-sidebar', true);
        } else {
            Cookies.remove('hide-sidebar');
        }
    });
    $('.category-toggle').on('click', function () {
       
        $("i", this).toggleClass("fa-plus-square-o fa-minus-square-o");
    });
});
