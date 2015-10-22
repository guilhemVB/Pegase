$().ready(function () {


    $(document).ready(function () {
        var select = $("#updateVoyageModal .select2");
        select.width("100%");

        $("#voyageName").val(currentVoyageName);
        $("#updateVoyageModal select").val(currentStartDestinationId).trigger("change");

        $('#deparatureDate #deparatureDateContainer').datepicker({
            language: "fr",
            format: 'dd-mm-yyyy',
            todayHighlight: true
        });

        $('#deparatureDate #deparatureDateContainer').datepicker('setDate', currentStartDate);
    });

    $("#updateVoyageModal form").submit(function (event) {
        event.preventDefault();

        var date = $('#deparatureDate #deparatureDateContainer').datepicker('getDate');

        var data = {
            name: $('#voyageName').val(),
            deparatureDate: date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(),
            destinationId: $('#updateVoyageModal #updateDestination').val()
        };

        $("#updateVoyageModal form button").button('loading');

        $.post(voyageCRUDUpdateUrl, data, function (response) {
            document.location.href = response.nextUri;
        }, "json");
    });


});
