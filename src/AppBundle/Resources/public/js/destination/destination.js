$().ready(function () {

    $(document).ready(function () {
        if (typeof(Maplace) !== 'undefined') {
            new Maplace({
                locations: maplaceData,
                map_div: '#gmap',
                controls_on_map: false
            }).Load();
        }
    });

    //$('#addToVoyageModal').on('show.bs.modal', function (event) {
    //    var button = $(event.relatedTarget);
    //    var destinationName = button.data('destinationName');
    //
    //    $("#numberDays").val(7);
    //
    //    var modal = $(this);
    //    modal.find('.destinationNameModal').text(destinationName);
    //});
    //
    //$("#addToVoyageBtn").on("click", function() {
    //    var data = {
    //        nbDays : $("#numberDays").val(),
    //        addBtnAddToVoyage : true
    //    };
    //    $.post(addStageUrl, data, function (response) {
    //        $.updateNavBarInfos();
    //        $("#btnAddToVoyage").html(response.btnAddToVoyage);
    //    }, "json");
    //});
    //
    //$('#removeFromVoyageModal').on('show.bs.modal', function (event) {
    //    var button = $(event.relatedTarget);
    //    var destinationName = button.data('destinationName');
    //    var stageId = button.data('stageId');
    //    var nbDays = button.data('stageNbDays');
    //    var position = button.data('stagePosition');
    //
    //    var modal = $(this);
    //    modal.find('.destinationNameModal').text(destinationName);
    //    modal.find('#nbDaysRemoveFromVoyageModal').html(nbDays);
    //    modal.find('#positionRemoveFromVoyageModal').html(position);
    //
    //    var validationBtn = modal.find("#removeFromVoyageBtn");
    //    validationBtn.data('stageId', stageId);
    //});
    //
    //$("#removeFromVoyageBtn").on('click', function (event) {
    //    var data = {
    //        addBtnAddToVoyage : true
    //    };
    //    var url = removeStageUrl.replace("0", $(this).data("stageId"));
    //    $.post(url, data, function (response) {
    //        $.updateNavBarInfos();
    //        $("#btnAddToVoyage").html(response.btnAddToVoyage);
    //    }, "json");
    //});

});
