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

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $.material.init();
});
