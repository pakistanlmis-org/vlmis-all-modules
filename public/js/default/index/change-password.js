//jQuery.validator.addMethod("uppercase", function (value) {
//    return /[A-Z]/.test(value);
//}, 'Your input must contain at least 1 capital letter');
//
//jQuery.validator.addMethod("lowercase", function (value) {
//    return /[a-z]/.test(value);
//}, 'Your input must contain at least 1 lowercase letter');
//
//jQuery.validator.addMethod("digits", function (value) {
//    return /\d/.test(value);
//}, 'Your input must contain at least 1 number');
//
//jQuery.validator.addMethod("specialcharacter", function (value) {
//    return /(?=.*[!@#$%^&*()_+])/.test(value);
//}, 'Your input must contain at least 1 special character');

$(function() {
    $("#form1").validate({
        rules: {
            old_pass: {
                required: true,
                remote: appName + "/index/check-old-password"
            },
            new_pass: {
                required: true,
                minlength: 6,
                maxlength: 20
//                uppercase: true,
//                lowercase: true,
//                digits: true,
//                specialcharacter: true
            },
            confirm_pas: {
                required: true,
                equalTo: '#new_pass'
            }
        },
        messages: {
            old_pass: {
                required: 'Enter old password.',
                remote: 'Wrong old password'
            },
            new_pass: {
                required: 'Enter new password.'
            },
            confirm_pas: {
                required: 'Confirm your password.',
                equalTo: 'Type the same password again. '
            }
        }
    });
})