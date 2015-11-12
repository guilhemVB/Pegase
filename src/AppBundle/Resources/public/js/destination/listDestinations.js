$().ready(function () {

    var $destinationSelect = $("#destinationsListView .destination");

    $destinationSelect.select2({
        placeholder: "Rechercher une destination",
        theme: "bootstrap"
    });

    $("#btnSeeDestination").on('click', function() {
        var data = $destinationSelect.select2('data');
        window.location.href = data.element[0].dataset.href;
    });

});
