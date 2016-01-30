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

    $("#updateVoyageModal #_submitUpdateForm").on("click", function () {

        $hasError = false;
        if ($('#voyageName').val()) {
            $("#errorBlockName").addClass("hidden");
        } else {
            $hasError = true;
            $("#errorBlockName").removeClass("hidden");
        }

        var date = $('#deparatureDate #deparatureDateContainer').datepicker('getDate');
        if (date) {
            $("#errorBlockDeparatureDate").addClass("hidden");
        } else {
            $hasError = true;
            $("#errorBlockDeparatureDate").removeClass("hidden");
        }

        if ($('#updateVoyageModal #updateDestination').val()) {
            $("#errorBlockDestination").addClass("hidden");
        } else {
            $hasError = true;
            $("#errorBlockDestination").removeClass("hidden");
        }

        if (!$hasError) {
            $(this).parent().children().addClass("disabled");
            saveVoyage();
        }
    });

    function saveVoyage() {
        var date = $('#deparatureDate #deparatureDateContainer').datepicker('getDate');

        var data = {
            name: $('#voyageName').val(),
            deparatureDate: date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(),
            destinationId: $('#updateVoyageModal #updateDestination').val()
        };

        $.post(voyageCRUDUpdateUrl, data, function (response) {
            document.location.href = response.nextUri;
        }, "json");
    }
});
