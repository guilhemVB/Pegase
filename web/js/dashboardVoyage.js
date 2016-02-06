$().ready(function () {

    $('[data-toggle=confirmation]').confirmation();

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
            var destinationName = country.element[0].attributes.getNamedItem("data-destination-name");
            if(destinationName) {
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

        $("#containerAddDestination form button").button('loading');
        disabledActions();
        var url = addStageUrl.replace("0", data.destinationId);
        $.post(url, data, function (response) {
            window.location.reload();
        }, "json");
    });

    $(".btnDeleteStage").on('click', function (e) {
        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
        disabledActions();
        var url = removeStageUrl.replace("0", $(this).data('stageId'));
        $.post(url, function (response) {
            window.location.reload();
        }, "json");
    });

    function disabledActions() {
        $("#containerAddDestination button").prop('disabled', true);
        $.sortaleElement.option("disabled", true);
        $.editableElement.not(".editable-open").editable('disable');
        $('[data-toggle=confirmation]').prop('disabled', true);
    }

    function enableActions() {
        $("#containerAddDestination button").prop('disabled', false);
        $.sortaleElement.option("disabled", false);
        $.editableElement.editable('enable');
        $('[data-toggle=confirmation]').prop('disabled', false);
    }

    $(document).ready(function () {

        $.sortaleElement = Sortable.create(listDestinations, {
            animation: 200,
            handle: ".drag-handle",
            onUpdate: function (evt) {
                var item = evt.item;
                if (evt.newIndex == evt.oldIndex) {
                    return;
                }
                var stageId = item.dataset.stageId;
                var data = {
                    newPosition: evt.newIndex, // index start by 1, but start destination is in ul list.
                    oldPosition: evt.oldIndex
                };
                var url = changePositionStageUrl.replace(0, stageId);
                disabledActions();
                $.post(url, data, function (response) {
                    enableActions();
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


    $.fn.editableform.buttons =
        '<button type="submit" class="btn btn-primary btn-raised btn-sm editable-submit">' +
        '<i class="fa fa-check"></i>' +
        '</button>' +
        '<button type="button" class="btn btn-default btn-raised btn-sm editable-cancel">' +
        '<i class="fa fa-times"></i>' +
        '</button>';
    $.fn.editable.defaults.mode = 'inline';
    $.fn.editableform.loading = '<div><i class="fa fa-spinner fa-spin"></i></div>';
    $(document).ready(function () {
        $.editableElement = $('.nbDaysStage').editable({
            type: 'select',
            url: changeNbDaysStageUrl,
            validate: function (value) {
                if ($(this).data('value') == value) {
                    return ;
                }
                var stageId = $(this).data('pk');
                var stagePrice = $('.stagePrice[data-stage-id="' + stageId + '"]');
                stagePrice.html('<i class="fa fa-spinner fa-spin"></i>');
                disabledActions();
            },
            success: function (response, newValue) {
                $.updateNavBarInfos();
                enableActions();
                updateStats(response.statsView);

                var stagePrice = $('.stagePrice[data-stage-id="' + response.stageId + '"]');
                var priceHtml = response.stagePrice + ' &euro;';
                stagePrice.html('<span class="label label-success">' + priceHtml + '</span>');

                $.each(response.voyageStats.stagesStats, function (stageId, stats) {
                    var stageStars = $('.stageStars[data-stage-id="' + stageId + '"]');
                    var stageStartDate = $('.stageStartDate[data-stage-id="' + stageId + '"]');
                    stageStars.html(stats.starsView);
                    stageStartDate.html(stats.dateFromFormated);
                });

                $('[data-toggle="tooltip"]').tooltip();
            },
            inputclass: 'selectEditable',
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
                {value: 35, text: '5 semaines'},
                {value: 42, text: '6 semaines'},
                {value: 49, text: '7 semaines'},
                {value: 56, text: '8 semaines'},
                {value: 60, text: '2 mois'},
                {value: 90, text: '3 mois'},
                {value: 120, text: '4 mois'},
                {value: 150, text: '5 mois'},
                {value: 180, text: '6 mois'}
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

    $("#updateVoyageModal #_submitUpdateForm").on("click", function () {

        $hasError = false;
        if ($('#voyageName').val()) {
            $("#errorBlockName").addClass("hidden");
        } else {
            $hasError = true;
            $("#errorBlockName").removeClass("hidden");
        }

        var date = $('#deparatureDate #deparatureDateContainer').datepicker('getDate');
        if (date) {
            $("#errorBlockDeparatureDate").addClass("hidden");
        } else {
            $hasError = true;
            $("#errorBlockDeparatureDate").removeClass("hidden");
        }

        if ($('#updateVoyageModal #updateDestination').val()) {
            $("#errorBlockDestination").addClass("hidden");
        } else {
            $hasError = true;
            $("#errorBlockDestination").removeClass("hidden");
        }

        if (!$hasError) {
            $(this).parent().children().addClass("disabled");
            saveVoyage();
        }
    });

    function saveVoyage() {
        var date = $('#deparatureDate #deparatureDateContainer').datepicker('getDate');

        var data = {
            name: $('#voyageName').val(),
            deparatureDate: date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(),
            destinationId: $('#updateVoyageModal #updateDestination').val()
        };

        $.post(voyageCRUDUpdateUrl, data, function (response) {
            document.location.href = response.nextUri;
        }, "json");
    }
});
