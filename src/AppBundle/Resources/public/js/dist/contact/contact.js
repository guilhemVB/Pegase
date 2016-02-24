$().ready(function () {

    $(".containerContact form").submit(function (event) {
        event.preventDefault();

        var data = {
            email: $('#email').val(),
            subject: $('#subject').val(),
            message: $('#message').val()
        };

        $.post(sendEmailUrl, data, function (response) {
            document.location.href = response.nextUri;
        }, "json");


        return false;
    });

});
