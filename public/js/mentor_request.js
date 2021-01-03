var editor;
$(document).ready(function () {
    var accept_btn;
    var s_id = null;


    $(".req_acc").on("click", function () {
        accept_btn = $(this);
        var std_id = $(this).parent().parent().find(".id").attr('id');
        s_id = std_id;
        swal({
            type: 'success',
            title: 'Accepted',
            text: 'Student request is accepted',
            showCloseButton: true,
            onClose: function () {
                console.log(std_id);
                accept_btn.parent().html("Accepted");
                $.ajax({
                    url: "/accept_request",
                    method: "POST",
                    data: {
                        std_id: std_id,
                        mentor_id: window.mentor_id,
                        _token: Laravel.csrfToken,
                    },
                });
                $("#group_modal").show();
                console.log("WP");
            },
        });
    });

    $(".req_rej").on("click", function () {
        var reject_btn;
        reject_btn = $(this);
        var std_id = $(this).parent().parent().find(".id").attr('id');

        swal({
            type: 'warning',
            title: 'Rejected',
            text: 'Student request is Rejected',
            showCloseButton: true,
            onClose: function () {
                reject_btn.parent().html("Rejected");
                $.ajax({
                    url: "/reject_request",
                    method: "POST",
                    data: {
                        std_id: std_id,
                        mentor_id: window.mentor_id,
                        _token: Laravel.csrfToken
                    },

//                  success:function (){
//                      window.location.reload();
//                  }

                });
                console.log("nothing");
            }
        });

    });
    $("#group_dismiss").on("click", function () {
        $('#group_modal').hide();
    });

    $("#group_select").on('change', function () {
        var name = $(this).find("option:selected").text();
        if (name == "Create a new Group") {
            $("#g_n").removeAttr('disabled');
        } else {
            $("#g_n").val(name);
            $("#g_n").attr('disabled', 'disabled');
        }
    });

    $("#submit_group").on("click", function () {
        var option = $("#group_select option:selected").val();
        var g_name = $("#g_n").val();

        console.log(g_name);
        $.ajax({
            url: "/create_group",
            method: "POST",
            data: {
                'group_name': g_name,
                'group_id': option,
                'std_id': s_id,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                if (data == "success") {
                    swal({
                        type: 'success',
                        title: 'Group Assigned',
                        text: 'Student has been added to the group',
                        showCloseButton: true,
                    });
                    window.location.reload();
                }
            }
        });
    });


    $("#group_table").DataTable();
    $('#group_table_paginate').addClass("pull-right");
    $('#group_table_filter').addClass("pull-right");
    $('#group_table_filter  :input').css("font-size", "15px");


    $('.delete_group').on("click", function () {
        var btn = $(this).parent().parent().find(".id").attr("id");

        console.log(btn);
        $.ajax({
            url: "/delete_group",
            method: "POST",
            data: {
                'group_name': btn,
                _token: Laravel.csrfToken,
            },
            success: function () {

                swal({
                    type: 'success',
                    title: 'Group Deleted',
                    text: 'Group has been deleted',
                    showCloseButton: true,
                });
                window.location.reload();

            }
        });
    });

    $(".edit_btn").on("click", function () {
        var current = $(this);
        var row = $(this).parent().parent();
        var topic = row.find(".p_t");
        var aoi = row.find(".p_a");
        var date = row.find(".p_d");
        
        var aoi_ip = aoi.html();
        var aoi_name = aoi_ip.split("</select>");
        aoi.html(aoi_name[0] + ">");
        aoi.find("select").removeAttr("hidden");
        
        var topic_ip = topic.html();
        var topic_name = topic_ip.split(">");
        topic.html(topic_name[0] + ">");
        topic.find("input").removeAttr("hidden");
        
        var date_ip = date.html();
        var date_name = date_ip.split(">");
        date.html(date_name[0]+">");
        date.find("input").removeAttr("hidden");
        
        current.addClass("hidden");
        current.parent().find(".save_btn").removeClass("hidden");
    });

    $(".save_btn").on("click", function () {
        console.log("G_SAVE");
        var current = $(this);
        var row = $(this).parent().parent();
        var topic = row.find(".p_topic").val();
        var aoi = row.find(".p_aoi").val();
        var aoi_text = row.find(".p_aoi option:selected").text();
        var date = row.find(".p_date").val();
        var std_id = row.find(".id").attr("id");
        console.log("SAVE");
        $.ajax({
            url: "/modified_std_prog_data",
            method: "POST",
            data: {
                project_topic: topic,
                area_of_interest: aoi,
                std_id: std_id,
                date: date,
                _token: Laravel.csrfToken,
            },
            success: function () {
                swal({
                    type: 'success',
                    title: 'Student information Saved',
                    text: 'Student information has been Modified Successfully',
                    showCloseButton: true,
                });
                row.find(".p_topic").attr("hidden", "true");
                row.find(".p_aoi").attr("hidden", "true");
                row.find(".p_date").attr("hidden","true");
                row.find(".p_t").append(topic);
                row.find(".p_a").append(aoi_text);
                row.find(".p_d").append(date);
                current.addClass("hidden");
                current.parent().find(".edit_btn").removeClass("hidden");
            }
        });
    });


});