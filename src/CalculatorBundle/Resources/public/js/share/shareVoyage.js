$().ready(function () {

    $(document).ready(function () {
        var myMap = new map({
            mapName : 'gmap'
        });

        myMap.enableLegend(true);
        myMap.enableInformationOnOver();
        //myMap.setClickActionSelectCountry();
        myMap.printCountries(myMap.onEachFeatureCountry, listCountriesInVoyage);

        $('[data-toggle="tooltip"]').tooltip();
    });

});
