var select = $("<option></option>")
        .attr("value", "n")
        .text("Select Subphase");
$(document).ready(function () {
//    $('#my-select').MultiSelect();
//    $('#multiselect1').multiselect();


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
                $(".subphase_option").empty().append(select);
                for (var i = 0; i < data.length; i++) {
                    $(".subphase_option").append($("<option></option>")
                            .attr("value", data[i]["id"])
                            .text(data[i]["name"]));

                }

            }
        });
    });

    $('.phase_sub').on('click',function(){
        var subphase = $(".subphase_option option:selected").val();
        $.ajax({
            url:'/give_student_sub',
            method: "POST",
            data:{
                subphase: subphase,
                _token : Laravel.csrfToken,
            },
            success : function(data){
                $(".to_select").empty();
                $(".from_select").empty();
                var dd = data["sub"];
                var diff = data["diff"];
                for (var i = 0; i < dd.length; i++) {
                    $(".to_select").append($("<option></option>")
                            .attr("value", dd[i]["id"])
                            .text(dd[i]["username"]));

                }
                for (var i = 0; i < diff.length; i++) {
                    $(".from_select").append($("<option></option>")
                            .attr("value", diff[i]["id"])
                            .text(diff[i]["username"]));

                }
            }
        })
         
    });


    $('.phase_opt').on('change', function () {
        var option = this.value;
        $.ajax({
            url: "/get_phase_option",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                phase_id: option
            },
            success: function (data) {
                $(".sub_opt").empty().append(select);
                for (var i = 0; i < data.length; i++) {
                    $(".sub_opt").append($("<option></option>")
                            .attr("value", data[i]["id"])
                            .text(data[i]["name"]));

                }
            }
        });
    });
    $('.see_std').on('click', function () {
        $("#subphase_datatable").html("");
        var phase = $(".phase_opt option:selected").val();
        var subphase = $(".sub_opt option:selected").val();
        console.log("WP");
        $.ajax({
            url: "/give_std_subphase",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                phase: phase,
                subphase: subphase,
            },
            success: function (data) {
                $("#std_details").html(data);
                $("#subphase_datatable").DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            footer: false,
                        }
                    ]
                });
                $(".buttons-excel").css("font-size", "13px");
                $('#p_dt').find('.row').css({"width": "100%", "margin": "0"});
                $('#subphase_datatable_paginate').addClass("pull-right");
                $('#subphase_datatable_filter').addClass("pull-right");
                $('#subphase_datatable_filter  :input').css("font-size", "15px");
                $('#subphase_datatable_length').find('.form-control').css("font-size", "13px");
            }
        });
    });





});    