$().ready(function () {

    var maplace = null;
    $(document).ready(function () {
        maplace = new Maplace({
            locations: maplaceData,
            map_div: '#gmap',
            controls_on_map: false,
            type: 'polyline'
        }).Load();
    });


    var $numberDays = $('#numberDays'),
        $destination = $('#containerAddDestination #addDestination');

    function format(country) {
        if (country.id) {
            return country.text;
        }
        var countryCode = country.element[0].attributes.getNamedItem("data-country-code").value;

        return "<img class='flagSelectDestination flagCountrySmall' src='http://www.geonames.org/flags/x/" + countryCode.toLowerCase() + ".gif'/>" + country.text;
    }

    $(".destination").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) { return m; },
        placeholder: "Choisir une destination",
        theme: "bootstrap"
    });


    $("#containerAddDestination button").on("click", function (event) {
        $("#errorBlockDestination_addDestination").addClass("hidden");
        if (!$destination.val()) {
            $("#errorBlockDestination_addDestination").removeClass("hidden");
        }
    });

    $("#containerAddDestination form").submit(function (event) {
        event.preventDefault();

        var data = {
            nbDays: $numberDays.val(),
            destinationId: $destination.val()
        };

        if (data.destinationId === "") {
            return;
        }

        //$("#containerAddDestination form button").button('loading');
        var url = addStageUrl.replace("0", data.destinationId);
        $.post(url, data, function (response) {
            window.location.reload();
        }, "json");
    });

    $(".btnDeleteStage").on('click', function (e) {
        $(this).button('loading');
        var url = removeStageUrl.replace("0", $(this).data('stageId'));
        $.post(url, function (response) {
            window.location.reload();
        }, "json");
    });


    //doc sortable :  https://github.com/RubaXa/Sortable/wiki/Sortable-v1.0-%E2%80%94-New-capabilities
    $(document).ready(function () {

        Sortable.create(listDestinations, {
            animation: 200,
            onUpdate: function (evt) {
                var item = evt.item;
                if (evt.newIndex == evt.oldIndex) {
                    return;
                }
                var stageId = item.dataset.stageId;
                var data = {
                    newPosition: evt.newIndex + 1,
                    oldPosition: evt.oldIndex + 1
                };
                var url = changePositionStageUrl.replace(0, stageId);
                $.post(url, data, function (response) {

                    maplace.Load({
                        locations: response.maplaceData,
                        map_div: '#gmap',
                        controls_on_map: false,
                        type: 'polyline'
                    });

                    updateStats(response.statsView);

                }, "json");
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    function updateStats(statsView) {
        var $dashboardStatsContainer = $("#dashboardStatsContainer");
        $dashboardStatsContainer.empty();
        $dashboardStatsContainer.append(statsView);
    }


    $.fn.editable.defaults.mode = 'inline';
    $(document).ready(function () {
        $('.nbDaysStage').editable({
            type: 'select',
            url: changeNbDaysStageUrl,
            validate: function (value) {
                var stageId = $(this).data('pk');
                var $stagePrice = $('.stagePrice[data-stage-id="' + stageId + '"]');
                $stagePrice.html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
                $stagePrice.addClass('editableform-loading');
            },
            success: function (response, newValue) {
                $.updateNavBarInfos();
                updateStats(response.statsView);

                var $stagePrice = $('.stagePrice[data-stage-id="' + response.stageId + '"]');
                $stagePrice.removeClass('editableform-loading');
                var priceHtml = response.stagePrice + ' &euro;';
                $stagePrice.html(priceHtml);

                $.each(response.voyageStats.stagesStats, function (stageId, stats) {
                    var $stageStars = $('.stageStars[data-stage-id="' + stageId + '"]');
                    $stageStars.html(stats.starsView);
                });

                $('[data-toggle="tooltip"]').tooltip();
            },
            source: [
                {value: 1, text: '1 jour'},
                {value: 2, text: '2 jours'},
                {value: 3, text: '3 jours'},
                {value: 4, text: '4 jours'},
                {value: 5, text: '5 jours'},
                {value: 6, text: '6 jours'},
                {value: 7, text: '7 jours'},
                {value: 8, text: '8 jours'},
                {value: 9, text: '9 jours'},
                {value: 10, text: '10 jours'},
                {value: 11, text: '11 jours'},
                {value: 12, text: '12 jours'},
                {value: 13, text: '13 jours'},
                {value: 14, text: '14 jours'},
                {value: 15, text: '15 jours'},
                {value: 16, text: '16 jours'},
                {value: 17, text: '17 jours'},
                {value: 18, text: '18 jours'},
                {value: 19, text: '19 jours'},
                {value: 20, text: '20 jours'},
                {value: 21, text: '21 jours'},
                {value: 22, text: '22 jours'},
                {value: 23, text: '23 jours'},
                {value: 24, text: '24 jours'},
                {value: 25, text: '25 jours'},
                {value: 26, text: '26 jours'},
                {value: 27, text: '27 jours'},
                {value: 28, text: '28 jours'},
                {value: 29, text: '29 jours'},
                {value: 30, text: '30 jours'},
                {value: 31, text: '31 jours'},
                {value: 32, text: '32 jours'},
                {value: 33, text: '33 jours'},
                {value: 34, text: '34 jours'},
                {value: 35, text: '35 jours'},
                {value: 36, text: '36 jours'},
                {value: 37, text: '37 jours'},
                {value: 38, text: '38 jours'},
                {value: 39, text: '39 jours'},
                {value: 40, text: '40 jours'},
                {value: 41, text: '41 jours'},
                {value: 42, text: '42 jours'},
                {value: 43, text: '43 jours'},
                {value: 44, text: '44 jours'},
                {value: 45, text: '45 jours'},
                {value: 46, text: '46 jours'},
                {value: 47, text: '47 jours'},
                {value: 48, text: '48 jours'},
                {value: 49, text: '49 jours'},
                {value: 50, text: '50 jours'},
                {value: 51, text: '51 jours'},
                {value: 52, text: '52 jours'},
                {value: 53, text: '53 jours'},
                {value: 54, text: '54 jours'},
                {value: 55, text: '55 jours'},
                {value: 56, text: '56 jours'},
                {value: 57, text: '57 jours'},
                {value: 58, text: '58 jours'},
                {value: 59, text: '59 jours'},
                {value: 60, text: '60 jours'}
            ]
        });
    });

});

$().ready(function () {


    $(document).ready(function () {
        var select = $("#updateVoyageModal .select2");
        select.width("100%");

        $("#voyageName").val(currentVoyageName);
        $("#updateVoyageModal select").val(currentStartDestinationId).trigger("change");

        $('#deparatureDate #deparatureDateContainer').datepicker({
            language: "fr",
            format: 'dd-mm-yyyy',
            todayHighlight: true
        });

        $('#deparatureDate #deparatureDateContainer').datepicker('setDate', currentStartDate);
    });

    $("#updateVoyageModal form").submit(function (event) {
        event.preventDefault();

        var date = $('#deparatureDate #deparatureDateContainer').datepicker('getDate');

        var data = {
            name: $('#voyageName').val(),
            deparatureDate: date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(),
            destinationId: $('#updateVoyageModal #updateDestination').val()
        };

        $("#updateVoyageModal form button").button('loading');

        $.post(voyageCRUDUpdateUrl, data, function (response) {
            document.location.href = response.nextUri;
        }, "json");
    });


});
