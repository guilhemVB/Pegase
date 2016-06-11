$().ready(function () {

    function format(country) {
        if (country.id) {
            var destinationName = country.element[0].attributes.getNamedItem("data-destination-name");
            if (destinationName) {
                return destinationName.value;
            }
            return country.text;

        }
        var countryCode = country.element[0].attributes.getNamedItem("data-country-code").value;
        return "<img class='flagSelectDestination flagCountrySmall' src='http://www.geonames.org/flags/x/" + countryCode.toLowerCase() + ".gif'/>" + country.text;

    }

    $(".destination").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function (m) {
            return m;
        },
        placeholder: "Choisir une destination",
        theme: "bootstrap"
    });

    $("#seeJourneyForm").submit(function (event) {
        event.preventDefault();

        if ($('#fromDestination').val() === "" || $('#toDestination').val() === "") {
            return;
        }

        seeJourney($('#fromDestination').val(), $('#toDestination').val());
    });

    $(".btn-add-journey").on('click', function() {
        seeJourney($(this).data('fromDestinationId'), $(this).data('toDestinationId'));
    });

    function seeJourney(fromDestinationId, toDestinationId) {
        var data = {
            fromDestinationId: fromDestinationId,
            toDestinationId: toDestinationId
        };

        $("#seeJourneyForm button").button('loading');
        $(".btn-add-journey").button('loading');

        $.get(getJourneyUrl, data, function (response) {
            $('#availableJourneyModal').modal('show');

            $('#fromDestinationModalName').text(response.fromDestination.name);
            $('#fromDestinationModalId').val(response.fromDestination.id);

            $('#toDestinationModalName').text(response.toDestination.name);
            $('#toDestinationModalId').val(response.toDestination.id);

            $('#latAndLon').text(response.toDestination.latitude + ';' + response.toDestination.longitude +
            '/' + response.fromDestination.latitude + ';' + response.fromDestination.longitude);

            $("#busTime").val(response.busTime);
            $("#busPrices").val(response.busPrices);

            $("#trainTime").val(response.trainTime);
            $("#trainPrices").val(response.trainPrices);

            $("#flyTime").val(response.flyTime);
            $("#flyPrices").val(response.flyPrices);

            $("#availableJourneyId").val(response.id);

            $("#seeJourneyForm button").button('reset');
            $(".btn-add-journey").button('reset');
            $('#fromDestination').val(null).trigger("change");
            $('#toDestination').val(null).trigger("change");
        }, "json");
    }

    $("#editAvailableJourney").submit(function (event) {
        event.preventDefault();

        $("#editAvailableJourney button").button('loading');

        $.post(editJourneyUrl, $("#editAvailableJourney").serialize(), function (response) {
            location.reload();
        }, "json");
    });

});
