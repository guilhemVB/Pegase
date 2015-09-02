$().ready(function () {

    $('#addToVoyageModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var destinationName = button.data('destinationName');

        var modal = $(this);
        modal.find('#destinationNameModal').text(destinationName);
    });


});
