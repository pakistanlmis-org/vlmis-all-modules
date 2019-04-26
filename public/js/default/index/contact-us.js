$(document).ready(function () {

    $("<a href='#' id='refresh_code' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-refresh'></span> Refresh code</a>").insertBefore("#captcha-input");

    $("#reset").click(function () {
        $('input[type="text"]').attr('value', "");
        $('#message').html('');
    });
    $("#saved").fadeOut(4000);

    $('#refresh_code').click(function () {
     
        $.ajax({
            type: "POST",
            url: appName + "/index/ajax-refresh-captcha",
            dataType: 'json',
            success: function (data) {
                $('#contact-us img').attr('src', data.src);
                $('#captcha-id').attr('value', data.id);
            }
        });

    });

    $(function () {

        $("#contact-us").validate({
            rules: {
                name: {
                    required: true,
                    alphaspace: true,
                    minlength: 3
                },
                e_mail: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    number: true
                },
                department: {
                    required: true,
                    alphanumspace: true
                },
                message: {
                    required: true,
                    alphanumspacehash: true,
                    maxlength: 400
                },
                "captcha[input]": {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    alphaspace: "Please enter alphabet only"
                },
                e_mail: {
                    required: "Please enter your email"

                },
                phone: {
                    required: "Please enter your phone number",
                    pakphone: "Please enter valid phone number"
                },
                department: {
                    required: "Please enter your department",
                    alphanumspace: "Alphanumeric only"
                },
                message: {
                    required: "Please enter your message",
                    alphanumspacehash: "Please alphabets, numbers, comma, question mark, exclamation mark and number sign only",
                    maxlength: "Please enter upto 400 characters"
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





