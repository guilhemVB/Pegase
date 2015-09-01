$().ready(function () {

    $('#deparatureDate').datepicker();

    $("#destinations").select2({
        placeholder: "Choix d'un lieu de départ"
    });
});
