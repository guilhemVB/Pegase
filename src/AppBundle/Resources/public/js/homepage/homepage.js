$().ready(function () {
    var myMap = new map({
        mapName : 'gmap'
    });

    myMap.enableLegend();
    myMap.printCountries(myMap.onEachFeatureCountry);
    myMap.enableInformationOnOver();
    myMap.setClickActionFollowURL();
});
