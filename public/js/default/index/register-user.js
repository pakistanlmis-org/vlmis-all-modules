

$(document).ready(function () {

    $("<a href='#' id='refresh_code' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-refresh'></span> Refresh code</a>").insertBefore("#captcha-input");
    $("#reset").click(function () {
        $('input[type="text"]').attr('value', "");
        $('#message').html('');
    });
    $("#saved").fadeOut(9000);
    $('#refresh_code').click(function () {

        $.ajax({
            type: "POST",
            url: appName + "/index/ajax-refresh-captcha",
            dataType: 'json',
            success: function (data) {
                $('#register-user img').attr('src', data.src);
                $('#captcha-id').attr('value', data.id);
            }
        });
    });
    $(function () {

        $("#register-user").validate({
            rules: {
                e_mail: {
                    required: true,
                    email: true,
                    remote: {
                        url: appName + "/index/ajax-check-email",
                        type: "post",
                        data: {
                            username: function () {
                                return $("#username").val();
                            }
                        }
                    }
                },
                country: {
                    required: true
                },
                organization: {
                    required: true,
                    alphanumspace: true
                },
                address: {
                    alphanumspacehash: true,
                    maxlength: 100
                },
                "captcha[input]": {
                    required: true
                }
            },
            messages: {
                e_mail: {
                    required: "Please enter your email",
                    remote: "Email already rigistered"
                },
                country: {
                    required: "Please select your country"
                },
                organization: {
                    required: "Please enter your organization",
                    alphanumspace: "Alphanumeric only"
                },
                address: {
                    alphanumspacehash: "Please alphabets, numbers, comma, question mark, exclamation mark and number sign only",
                    maxlength: "Please enter upto 100 characters"
                },
                "captcha[input]": {
                    required: "Please enter the above code"
                }
            }
        });
        $.validator.addMethod("alphaspace", function (value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
        });
        $.validator.addMethod("alphanumspace", function (value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z0-9\-\s]+$/);
        });
        $.validator.addMethod("alphanumspacehash", function (value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z0-9\-\s\#\,\.\?\!]+$/);
        });
        $.validator.addMethod("pakphone", function (value, element) {
            return this.optional(element) || value == value.match(/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/);
        });
    });
});





