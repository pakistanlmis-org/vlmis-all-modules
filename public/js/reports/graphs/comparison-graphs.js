$(function() {
    $("#optvals").change(function() {
        $("#prov_combo").hide();
        $("#dist_combo").hide();
        $("#all_provinces").empty();
        $("#all_districts").empty();
        var optvals = $(this).val();
        if (optvals == 1) {
            $("#yearcomp").attr("multiple", "multiple");
            $("#yearcomp").attr("name", "yearcomp[]");
        }
        if (optvals == 2 || optvals == 3 || optvals == 7 || optvals == 8) {
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-one",
                data: {office: 2},
                dataType: 'html',
                success: function(data) {
                    if (optvals == 7) {
                        $("#all_provinces").attr("multiple", "multiple");
                        $("#all_provinces").attr("name", "all_provinces[]");
                        $("#yearcomp").removeAttr("multiple", "multiple");
                        $("#yearcomp").attr("name", "yearcomp");
                    } else {
                        $("#all_provinces").removeAttr("multiple", "multiple");
                        $("#all_provinces").attr("name", "all_provinces");
                        $("#yearcomp").attr("multiple", "multiple");
                        $("#yearcomp").attr("name", "yearcomp[]");
                    }
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
        if (optvals == 3 || optvals == 8) {
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-two",
                data: {combo1: prov_id, office: 5},
                dataType: 'html',
                success: function(data) {
                    if (optvals == 8) {
                        $("#all_districts").attr("multiple", "multiple");
                        $("#all_districts").attr("name", "all_districts[]");
                        $("#yearcomp").removeAttr("multiple", "multiple");
                        $("#yearcomp").attr("name", "yearcomp");
                    } else {
                        $("#all_districts").removeAttr("multiple", "multiple");
                        $("#all_districts").attr("name", "all_districts");
                        $("#yearcomp").attr("multiple", "multiple");
                        $("#yearcomp").attr("name", "yearcomp[]");
                    }
                    $("#dist_combo").show();
                    $("#all_districts").html(data);
                }
            });
        }
    });
});

$('#submit_button').click(function(e) {
    var compare_option = $('#optvals').val();
    // Validation
    if (jQuery.inArray(parseInt(compare_option), [1, 2, 3]) != -1) {

        if ($('[name="products[]"]:checked').length == 0) {
            alert('Select at least one product');
            return false;
        }
        if ($('[name="yearcomp[]"] option:selected').length < 2) {
            alert('Select at least two years');
            return false;
        }

        if (jQuery.inArray(parseInt(compare_option), [2, 3]) != -1 && $('[name="all_provinces"]').val() == '') {
            alert('Select province');
            return false;
        }
        if (jQuery.inArray(parseInt(compare_option), [3]) != -1 && $('[name="all_districts').val() == '') {
            alert('Select district');
            return false;
        }

    }

    if (jQuery.inArray(parseInt(compare_option), [7, 8]) != -1) {
        if (compare_option == 7) {
            if ($('[name="products[]"]:checked').length == 0) {
                alert('Select at least one product');
                return false;
            }
            if ($('[name="all_provinces[]"] option:selected').length < 2) {
                alert('Select at least two provinces');
                return false;
            }
        } else if (compare_option == 8) {
            if ($('[name="products[]"]:checked').length == 0) {
                alert('Select at least one product');
                return false;
            }
            if ($('[name="province"]').val() == '') {
                alert('Select province');
                return false;
            }
            if ($('[name="all_districts[]"] option:selected').length < 2) {
                alert('Select at least two districts');
                return false;
            }
        }
    }

});
	