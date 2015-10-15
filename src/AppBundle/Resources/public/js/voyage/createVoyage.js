$().ready(function () {

    $('#deparatureDate #deparatureDateContainer').datepicker({
        language: "fr",
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        startDate: new Date()
    });

    $("#createVoyage .destination").select2({
        placeholder: "Choix d'un lieu de départ",
        theme: "bootstrap"
    });

    $("form").submit(function (event) {
        event.preventDefault();

        var date =  $('#deparatureDate #deparatureDateContainer').datepicker('getDate');

        var data = {
            name: $('#voyageName').val(),
            deparatureDate: date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(),
            destinationId: $('#createVoyage .destination').val()
        };

        $("form button").button('loading');

        $.post(voyageCRUDCreateUrl, data, function (response) {
            document.location.href = response.nextUri;
        }, "json");
    });
});
