$().ready(function () {

    var $destinationSelect = $("#destinationsListView .destination");
    var $btnSeeDestination = $("#btnSeeDestination");
    var $formGroupDestinations = $("#formGroupDestinations");

    $(document).ready(function () {
        if (typeof(Maplace) !== 'undefined') {
            new Maplace({
                locations: maplaceData,
                map_div: '#gmap',
                controls_on_map: false
            }).Load();
        }
    });

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

    $destinationSelect.select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function (m) {
            return m;
        },
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
