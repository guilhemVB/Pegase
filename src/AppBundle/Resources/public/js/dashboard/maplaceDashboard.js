$().ready(function () {

    $(document).ready(function () {
        new Maplace({
            locations: maplaceData,
            map_div: '#gmap',
            controls_on_map: false,
            type: 'polyline'
        }).Load();
    });
});
