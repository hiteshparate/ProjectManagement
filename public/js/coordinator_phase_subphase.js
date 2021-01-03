$(document).ready(function () {
    $('#phase_datatable').DataTable();
    $('#p_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#phase_datatable_paginate').addClass("pull-right");
    $('#phase_datatable_filter').addClass("pull-right");
    $('#phase_datatable_filter  :input').css("font-size", "15px");
    $('#phase_datatable_length').find('.form-control').css("font-size", "13px");

    $('#subphase_datatable').DataTable();
    $('#z_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#subphase_datatable_paginate').addClass("pull-right");
    $('#subphase_datatable_filter').addClass("pull-right");
    $('#subphase_datatable_filter  :input').css("font-size", "15px");
    $('#subphase_datatable_length').find('.form-control').css("font-size", "13px");

    $('.submission_yes').on('click', function () {
        $('.sub_cons').removeAttr('hidden');

    });
    $('.submission_no').on('click', function () {
        $('.sub_cons').attr('hidden', 'true');
    });

    $('.edit_phase').attr("disabled", "disabled");

    $('.phase_op').change(function () {
        if ($('.phase_op').val() == "n" || $('.phase_op option:selected').text() == "final_phase") {
            $('.edit_phase').attr("disabled", "disabled");            

        } else {
            $('.edit_phase').removeAttr("disabled");
        }
    });

    $('.edit_phase').on('click', function () {
        $(".hid_phase_id").attr("value", $(".phase_op").val());
        console.log($(".phase_op").val());
        $('.show_phase').attr('hidden', 'true');
        $('.edit_p').removeAttr('hidden');
        $('.edit_in').val($('.phase_op :selected').text());
    });

    $('.phase_option').on('change', function () {
        var option = this.value;
        $.ajax({
            url: "/get_phase_option",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                phase_id: option
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

    $('.edit_subphase').attr("disabled", "disabled");

    $('.subphase_option').change(function () {
        if ($('.subphase_option').val() != "n" && $('.phase_option').val() != "n") {
            $('.edit_subphase').removeAttr("disabled");
        } else {
            $('.edit_subphase').attr("disabled", "disabled");

        }
    });
    $('.edit_subphase').on('click', function () {
        $('.show_subphase').attr('hidden', 'true');
        $('.sub_detail').removeAttr('hidden');
        $('.edit_sub').val($('.subphase_option :selected').text());
        if($('.subphase_option option:selected').text() == "final_subphase"){
            $(".edit_sub").attr("readonly",true);
        }
        
        var sub = $('.subphase_option').val();
        $.ajax({
            url: '/edit_subphase',
            method: 'POST',
            data: {
                subphase_id: sub,
                _token: Laravel.csrfToken
            },
            success: function (data) {
//                console.log(data);
                $('.hid_sub_id').attr('value', sub);
                $('#subphase_code').val(data["code"]);
                $('#grading_type').val(data["g_id"]);
                if (data["evaluation_committee"] == 0) {
                    $('#e_committee_no').prop('checked', true);
                } else {
                    $('#e_committee_yes').prop('checked', true);
                }
                if (data["submission"] == 0) {
                    $('#e_submission_no').prop('checked', true);
                    $('#e_submission_no').click();

                } else {
                    $('#e_submission_yes').prop('checked', true);
                    $('#e_submission_yes').click();

                    $('#file_name').val(data["file_name"]);
                    $('#file_size').val(data["file_size"]);
                    $('#file_ext').val(data["file_extension"]);


                }

            }

        });
    });




});