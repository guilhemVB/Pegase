$().ready(function () {


    $('#deparatureDate div').datepicker({
        language: "fr",
        format: 'dd-mm-yyyy',
        startDate: new Date(),
        todayHighlight: true
    });

    $('#updateVoyageModal').on('show.bs.modal', function (event) {
        var select = $("#updateVoyageModal .select2");
        select.width("100%");

        $("#voyageName").val(currentVoyageName);
        $("#updateVoyageModal select").val(currentStartDestinationId).trigger("change");

        debugger;
        //var modal = $(this);
        //modal.find('.destinationNameModal').text(destinationName);
    });


});
