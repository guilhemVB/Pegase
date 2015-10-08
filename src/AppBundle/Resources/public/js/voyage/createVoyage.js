$().ready(function () {

    $('#deparatureDate').datepicker({
        format: 'dd-mm-yyyy'
    });

    $("#createVoyage #destination").select2({
        placeholder: "Choix d'un lieu de départ",
        theme: "bootstrap"
    });

    $("form").submit(function (event) {
        event.preventDefault();

        var data = {
            name: $('#voyageName').val(),
            deparatureDate: $('#deparatureDate').val(),
            destinationId: $('#destination').val()
        };

        $.post(voyageCRUDCreateUrl, data, function (response) {
            if (true === response.success) {
                document.location.href = response.nextUri;
            }
        }, "json");
    });
});
