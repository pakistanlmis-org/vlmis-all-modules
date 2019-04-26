$(function() {
    $("#form1").validate({
        rules: {
            old_pass: {
                required: true,
                remote: appName + "/index/check-old-password"
            },
            new_pass: 'required',
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
            new_pass: 'Enter new password.',
            confirm_pas: {
                required: 'Confirm your password.',
                equalTo: 'Type the same password again. '
            }
        }
    });
})