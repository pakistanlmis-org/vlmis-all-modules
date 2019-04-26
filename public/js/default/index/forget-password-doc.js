$(document).ready(function () {
    $("#reset").click(function () {
        $('input[type="text"]').attr('value', "");

    });


    $("#forget-password").validate({
        rules: {
             e_mail: {
                    required: true,
                    email: true,
                    remote: {
                        url: appName + "/index/ajax-check-user",
                        type: "post",
                        data: {
                            username: function () {
                                return $("#username").val();
                            }
                        }
                    }
                }
        },
        messages: {
            e_mail: {
                required: 'Enter your email.',
                remote: "You are not registered."
            }
        }
    });


});