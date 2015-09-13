$().ready(function () {

    //doc sortable :  https://github.com/RubaXa/Sortable/wiki/Sortable-v1.0-%E2%80%94-New-capabilities

    $(document).ready(function () {
        Sortable.create(listDestinations, {
            animation: 200,
            onUpdate: function (evt) {
                var item = evt.item;
                if(evt.newIndex == evt.oldIndex) {
                    return;
                }
                var stageId = item.dataset.stageId;
                var data = {
                    newPosition: evt.newIndex + 1,
                    oldPosition: evt.oldIndex + 1
                };
                var url = changePositionStageUrl.replace(0, stageId);
                $.post(url, data, function (response) {
                }, "json");
            }
        });
    });
});
