$().ready(function () {
    var myMap = new map({
        mapName : 'gmap'
    });

    myMap.printDestinations(true);

    $(".vignetteDestination").hover(function(){
        var $marker = $(".marker[data-destinationId='" + $(this).data('destinationid') + "']");
        $marker.append("<div class='pulse'></div>")
    }, function(){
        var $marker = $(".marker[data-destinationId='" + $(this).data('destinationid') + "']");
        $marker.find(".pulse").remove();
    });

});
