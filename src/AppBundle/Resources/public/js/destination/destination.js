$().ready(function () {

    $('#addToVoyageModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var destinationName = button.data('destinationName');

        $("#numberDays").val(1);

        var modal = $(this);
        modal.find('#destinationNameModal').text(destinationName);
    });

    $("#addToVoyageBtn").on("click", function() {
        var data = {
            nbDays : $("#numberDays").val()
        };
        $.post(addStepUrl, data, function (response) {
            $("#btnAddToVoyage").html(response.btnAddToVoyage);
        }, "json");
    });

    $("#btnAddToVoyage").on('click', '#removeStage', function (event) {
        debugger;
        var url = removeStepUrl.replace("0", $(this).data("stageId"));
        $.post(url, function (response) {
            $("#btnAddToVoyage").html(response.btnAddToVoyage);
        }, "json");
    });

});
