$().ready(function () {

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $.material.init();


    $('.columnsFooter').masonry({
        itemSelector: '.country',
        columnWidth: ".country",
        percentPosition: true
    });

});
