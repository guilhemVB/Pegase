$().ready(function () {
    var myMap = new map({
            mapName : 'gmap',
            accessToken : accessTokenMapbox
        });

    myMap.enableLegend();
    myMap.printCountries(myMap.onEachFeatureCountry);
    myMap.enableInformationOnOver();
    myMap.setClickActionFollowURL();
});
