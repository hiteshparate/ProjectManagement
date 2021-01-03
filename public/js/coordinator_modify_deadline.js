var select = $("<option></option>")
        .attr("value", "n")
        .text("Select Subphase");
var select_event = $("<option></option>")
        .attr("value", "n")
        .text("Select Event");
$(document).ready(function () {
    $('.phase_option').on('change', function () {
        var option = this.value;
        $.ajax({
            url: "/get_phase_option",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                phase_id: option,
            },
            success: function (data) {
                $(".subphase_option").children('option:not(:first)').remove();
                for (var i = 0; i < data.length; i++) {
                    $(".subphase_option").append($("<option></option>")
                            .attr("value", data[i]["id"])
                            .text(data[i]["name"]));
                }
            }
        });
    });

    $(".edit_phase_option").on("change", function () {
        $(".edit_event_option").empty().append(select_event);
        var option = this.value;
        $.ajax({
            url: "/get_phase_option",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                phase_id: option,
            },
            success: function (data) {
                $(".edit_subphase_option").empty().append(select);
                for (var i = 0; i < data.length; i++) {
                    $(".edit_subphase_option").append($("<option></option>")
                            .attr("value", data[i]["id"])
                            .text(data[i]["code"]));
                }

            }
        });
        $(".edit_subphase_option").on("change", function () {
            var subphase = this.value;
            $.ajax({
                url: "/get_subphase_option",
                method: "POST",
                data: {
                    _token: Laravel.csrfToken,
                    subphase_id: subphase,
                },
                success: function (data) {
                    $(".edit_event_option").empty().append(select_event);
                    for (var i = 0; i < data.length; i++) {
                        $(".edit_event_option").append($("<option></option>")
                                .attr("value", data[i]["id"])
                                .text(data[i]["name"]));
                    }
                }
            });
        });
    });

    $('.edit_event').attr("disabled", "disabled");
    $('.edit_event_option').change(function () {
        $('#multiselect1') .find('option') .remove() .end();
        if ($('.edit_subphase_option').val() != "n" && $('.edit_phase_option').val() != "n" && $(".edit_event_option").val() != "n") {
            console.log("GG");
            var sub_id = $('.edit_subphase_option').val();
            $('.edit_event').removeAttr("disabled");
            $('.hide_show').removeAttr("hidden");
            $.ajax({
                url: '/show_student_subphase',
                method: "POST",
                data: {
                    sub_id: sub_id,
                    _token: Laravel.csrfToken
                },
                success: function (data) {
                    console.log(data);
                    
                    $.each(data, function (index , value) {
//                        $('<option value="' + value["id"] + '" style="font-size: 13px">' + value["username"] + '</option>').appendTo($("#multiselect1"));
                    $('#multiselect1') .find('option').end().append('<option value="'+value["id"] +'">'+ value["username"] +'</option>')  ;
                    });
                    
                }
            });

        } else {
            console.log("WP");
            $('.edit_event').attr("disabled", "disabled");
            $('#multiselect1') .find('option') .remove() .end();
        }
    });
    $('.edit_phase_option').change(function () {
        if ($('.edit_subphase_option').val() != "n" && $('.edit_phase_option').val() != "n" && $(".edit_event_option").val() != "n") {
            console.log("GG");
            $('.edit_event').removeAttr("disabled");
        } else {
            console.log("WP");
            $('.edit_event').attr("disabled", "disabled");
            $('#multiselect1') .find('option') .remove() .end();

        }
    });
    $('.edit_subphase_option').change(function () {
        if ($('.edit_subphase_option').val() != "n" && $('.edit_phase_option').val() != "n" && $(".edit_event_option").val() != "n") {
            console.log("GG");
            $('.edit_event').removeAttr("disabled");
        } else {
            console.log("WP");
            $('.edit_event').attr("disabled", "disabled");
            $('#multiselect1') .find('option') .remove() .end();

        }
    });




});