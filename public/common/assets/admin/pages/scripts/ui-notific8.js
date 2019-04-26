var UINotific8 = function () {
    return {
        //main function to initiate the module
        init: function () {
            $('#notific8_show').click(function (event) {
                var settings = {
                    theme: 'ebony',
                    sticky: false,
                    horizontalEdge: 'bottom',
                    verticalEdge: 'right'
                },
                $button = $(this);
                
                if ($.trim($('#notific8_heading').html()) != '') {
                    settings.heading = $.trim($('#notific8_heading').html());
                }

                if (!settings.sticky) {
                    settings.life = $('#notific8_life').html();
                }

                $.notific8('zindex', 11500);
                $.notific8($.trim($('#notific8_text').html()), settings);
                
                $button.attr('disabled', 'disabled');
                setTimeout(function () {
                    $button.removeAttr('disabled');
                }, 1000);
            });
        }
    };
}();