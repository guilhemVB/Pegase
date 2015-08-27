$().ready(function () {

    $(document).ready(function () {

        new Maplace({
            locations: [{
                lat: 48.880195,
                lon: 2.317879,
                zoom: 6,
                title: 'Paris',
                html: 'Paris'
            }, {
                lat: 35.939810,
                lon: 139.767143,
                zoom: 6,
                title: 'Tokyo',
                html: 'Tokyo'
            }],
            map_div: '#gmap',
            controls_type: 'list',
            controls_on_map: false,
            type: 'polyline',
            view_all_text: 'Tous'
        }).Load();


    });
});
