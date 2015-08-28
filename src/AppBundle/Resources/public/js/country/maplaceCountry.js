$().ready(function () {

    $(document).ready(function () {
        new Maplace({
            locations: maplaceData,
            map_div: '#gmap',
            controls_type: 'list',
            controls_on_map: false,
            view_all_text: 'Tous'
        }).Load();
    });
});
