$().ready(function () {

    var $destinationSelect = $("#destinationsListView .destination");
    var $btnSeeDestination = $("#btnSeeDestination");
    var $formGroupDestinations = $("#formGroupDestinations");

    $destinationSelect.select2({
        placeholder: "Rechercher une destination",
        theme: "bootstrap"
    });

    $btnSeeDestination.on('click', function () {
        var data = $destinationSelect.select2('data');
        if (data) {
            $formGroupDestinations.addClass('has-success');
            $formGroupDestinations.removeClass('has-error');
            window.location.href = data.element[0].dataset.href;
        } else {
            $formGroupDestinations.removeClass('has-success');
            $formGroupDestinations.addClass('has-error');
        }
    });

});
