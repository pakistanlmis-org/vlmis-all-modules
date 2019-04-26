$(function() {
    $("#optvals").change(function() {
        $("#prov_combo").hide();
        $("#dist_combo").hide();
        $("#all_provinces").empty();
        $("#all_districts").empty();
        var optvals = $(this).val();
        if (optvals == 10 || optvals == 11) {
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-one",
                data: {office: 2},
                dataType: 'html',
                success: function(data) {
                    $("#prov_combo").show();
                    $("#all_provinces").html(data);
                }
            });
        }
    });

    $("#all_provinces").change(function() {
        $("#dist_combo").hide();
        $("#all_districts").empty();
        var prov_id = $(this).val();
        var optvals = $("#optvals").val();
        if (optvals == 11) {
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-two",
                data: {combo1: prov_id, office: 5},
                dataType: 'html',
                success: function(data) {
                    $("#dist_combo").show();
                    $("#all_districts").html(data);
                }
            });
        }
    });
});

// Form submission
$('#submit_button').click(function(e) {
    var compare_option = $('#optvals').val();
    // Validation
    if ($('[name="products[]"]:checked').length == 0) {
        alert('Select at least one product');
        return false;
    }
    if ((compare_option == 10 || compare_option == 11) && $('#all_provinces').val() == '') {
        alert('Select province');
        return false;
    }
    if ((compare_option == 11) && $('#all_districts').val() == '') {
        alert('Select district');
        return false;
    }


});