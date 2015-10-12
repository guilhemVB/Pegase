$().ready(function () {

    $('#updateVoyageModal').on('show.bs.modal', function (event) {
        var select = $("#updateVoyageModal .select2");
        select.width("100%");

        $("#voyageName").val($("#currentVoyageName").html());

        $("#updateVoyageModal select").val($("#currentStartDestination").data("startDestinationId")).trigger("change");

        debugger;
        //var modal = $(this);
        //modal.find('.destinationNameModal').text(destinationName);
    });

});
