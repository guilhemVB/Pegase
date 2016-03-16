$().ready(function () {

    (function ($) {

        $.updateNavBarInfos = function (voyageId) {
            //if (navbarInfosUrl == null) {
            //    return;
            //}
            //var $navBarInfo = $("#navBarInfos");
            //$navBarInfo.empty();
            //$navBarInfo.append('<i class="fa fa-spinner fa-pulse"></i>');
            //
            //$.post(navbarInfosUrl, {}, function (response) {
            //    $navBarInfo.empty();
            //    $navBarInfo.append(response.viewInfos)
            //}, "json");
        };

    })($);

    $.updateNavBarInfos();

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
