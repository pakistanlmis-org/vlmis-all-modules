$("#consumption_comments").validate({
    rules: {
        'comments': {
            required: true

        }
    },
    messages: {
    },
    submitHandler: function (form) {
        RefreshParent();
        function RefreshParent() {
            var doc = window.opener.document,
                    theForm = doc.getElementById("dataEntryfrm");

            theForm.submit();
            form.submit();
            window.close();
        }

    }
});