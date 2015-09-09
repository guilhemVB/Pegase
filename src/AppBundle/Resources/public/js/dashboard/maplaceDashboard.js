$().ready(function () {

    $(document).ready(function () {
        console.log("maplace");
        new Maplace({
            locations: maplaceData,
            map_div: '#gmap',
            controls_on_map: false,
            type: 'polyline'
        }).Load();
    });
});
