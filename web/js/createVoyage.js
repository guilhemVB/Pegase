$().ready(function () {

    $('#deparatureDate #deparatureDateContainer').datepicker({
        language: "fr",
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        startDate: new Date()
    });

    function format(country) {
        if (country.id) {

            var destinationName = country.element[0].attributes.getNamedItem("data-destination-name");
            if(destinationName) {
                return destinationName.value;
            }

            return country.text;
        }
        var countryCode = country.element[0].attributes.getNamedItem("data-country-code").value;

        return "<img class='flagSelectDestination flagCountrySmall' src='http://www.geonames.org/flags/x/" + countryCode.toLowerCase() + ".gif'/>" + country.text;
    }

    $("#createVoyage .destination").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) { return m; },
        placeholder: "Choix d'un lieu de départ",
        theme: "bootstrap"
    });

    $("#createVoyage button").on("click", function() {
        if ($('#voyageName').val()) {
            $("#errorBlockName").addClass("hidden");
        } else {
            $("#errorBlockName").removeClass("hidden");
        }

        var date =  $('#deparatureDate #deparatureDateContainer').datepicker('getDate');
        if (date) {
            $("#errorBlockDeparatureDate").addClass("hidden");
        } else {
            $("#errorBlockDeparatureDate").removeClass("hidden");
        }

        if ($('#createVoyage #selectDestination').val()) {
            $("#errorBlockDestination").addClass("hidden");
        } else {
            $("#errorBlockDestination").removeClass("hidden");
        }
    });

    $("form").submit(function (event) {
        event.preventDefault();

        var date =  $('#deparatureDate #deparatureDateContainer').datepicker('getDate');

        var data = {
            name: $('#voyageName').val(),
            deparatureDate: date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(),
            destinationId: $('#createVoyage #selectDestination').val()
        };

        $("form button").button('loading');

        $.post(voyageCRUDCreateUrl, data, function (response) {
            document.location.href = response.nextUri;
        }, "json");
    });
});
