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

        $.post(addDestinationUrl, data, function (response) {
            debugger;
            //if (true === response.success) {
            //    location.reload();
            //} else {
            //    $createSpinsSpinner.hide();
            //    var message = response.error || 'Une erreur inconnue est survenue';
            //    $modalSpinAlert.append(message).show();
            //}
        }, "json");

    });

});
