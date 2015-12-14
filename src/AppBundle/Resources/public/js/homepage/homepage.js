$().ready(function () {

    // ---------- FACEBOOK ----------
    //window.fbAsyncInit = function() {
    //    FB.init({
    //        appId      : '1510306902598039',
    //        xfbml      : true,
    //        version    : 'v2.5'
    //    });
    //};
    //
    //(function(d, s, id){
    //    var js, fjs = d.getElementsByTagName(s)[0];
    //    if (d.getElementById(id)) {return;}
    //    js = d.createElement(s); js.id = id;
    //    js.src = "//connect.facebook.net/fr_FR/sdk.js";
    //    fjs.parentNode.insertBefore(js, fjs);
    //}(document, 'script', 'facebook-jssdk'));


    // ---------- TWITTER ----------
    !function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
        if (!d.getElementById(id)) {
            js = d.createElement(s);
            js.id = id;
            js.src = p + '://platform.twitter.com/widgets.js';
            fjs.parentNode.insertBefore(js, fjs);
        }
    }(document, 'script', 'twitter-wjs');

});
