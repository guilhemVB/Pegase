$().ready(function () {

    $('#deparatureDate').datepicker();

    $("#destination").select2({
        placeholder: "Choix d'un lieu de départ",
        theme: "bootstrap"
    });
});
