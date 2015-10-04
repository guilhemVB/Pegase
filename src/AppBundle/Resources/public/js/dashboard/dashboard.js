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
        $destination = $('#destination');

    $("#dashboard #destination").select2({
        placeholder: "",
        theme: "bootstrap"
    });


    $("#containerAddDestination form").submit(function (event) {
        event.preventDefault();
        $(".select2").removeClass("has-error");

        var data = {
            nbDays: $numberDays.val(),
            destinationId: $destination.val()
        };

        if (data.destinationId === "") {
            $(".select2").addClass("has-error");
            return;
        }

        $("#containerAddDestination form button").button('loading');
        var url = addStageUrl.replace("0", data.destinationId);
        $.post(url, data, function (response) {
            window.location.reload();
        }, "json");
    });

    $(".btnDeleteStage").on('click', function (e) {
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
                }, "json");
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
});
