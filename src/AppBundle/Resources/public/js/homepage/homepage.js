$().ready(function () {

    $("#destination").select2({
        placeholder: "Trouver une destination",
        theme: "bootstrap"
    });

    $("#destination").on("change", function(e) {
        document.location.href= e.target.selectedOptions.item().dataset.href;
    });
});
