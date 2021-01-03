$(document).ready(function () {
    $('#s_details_datatable tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search" style="width:100px"/>' );
    } );
    var table = $('#s_details_datatable').DataTable({
        'select': {
            'style': 'multi'
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                footer: false,
                exportOptions: {
                    columns: [0,1,3,5,6,7,8,9,11,12,13,14],
                }
            }
        ]

    });
    $(".buttons-excel").css("font-size","13px");
    
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#s_details_datatable_paginate').addClass("pull-right");
    $('#s_details_datatable_filter').addClass("pull-right");
    $('#s_details_datatable_filter  :input').css("font-size", "15px");
    $('#s_details_datatable_length').find('.form-control').css("font-size", "13px");

    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    $("#table_filter :input").each(function () {
        $(this).attr("checked", true);
        $(this).attr("data-columnindex", $(this).val());
    });
    $("#table_filter_checklist input:checkbox").on("change", function () {
        var tablecolumn = table.column($(this).val());
        tablecolumn.visible(!tablecolumn.visible());
    });

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
//                console.log(std_id);
                accept_btn.parent().html("Accepted");
                $.ajax({
                    url: "/accept_request_coordinator",
                    method: "POST",
                    data: {
                        std_id: std_id,
                        _token: Laravel.csrfToken,
                    },
                });
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
                    url: "/reject_request_coordinator",
                    method: "POST",
                    data: {
                        std_id: std_id,
                        _token: Laravel.csrfToken
                    },

                    success: function () {
                        window.location.reload();
                    }

                });
            }
        });

    });


    $(".remind_btn").on("click", function () {
        var reject_btn;
        reject_btn = $(this);
        var std_id = $(this).parent().parent().find(".id").attr('id');

        $.ajax({
            url: "/send_consent_form_reminder_std",
            method: "POST",
            data: {
                std_id: std_id,
                _token: Laravel.csrfToken
            },

            success: function () {
                swal({
                    type: 'success',
                    title: 'Reminder Sent',
                    text: 'Reminder has been sent to Student',
                    showCloseButton: true,
                })
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
        date.html(date_name[0] + ">");
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
        var date = row.find(".p_date").val();
        var aoi_text = row.find(".p_aoi option:selected").text();
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
                row.find(".p_date").attr("hidden", "true");
                row.find(".p_t").append(topic);
                row.find(".p_a").append(aoi_text);
                row.find(".p_d").append(date);
                current.addClass("hidden");
                current.parent().find(".edit_btn").removeClass("hidden");
            }
        });
    });

    $(".send_mail").on("click", function () {
        var students = table.rows('.selected').data().toArray();

        var std_id = [];
        for (var i = 0; i < students.length; i++) {
            std_id.push(students[i][2]);
        }
        $.ajax({
            url: "/send_mail_to_students",
            method: "post",
            data: {
                std_id: std_id,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                window.location.replace("email_template", data);
            }
        });

    });
    
    $(".remind_mentor").on("click",function(){
         var remind_btn;
        remind_btn = $(this);
        var std_id = $(this).parent().parent().find(".std_prg_id").attr('id');
        $.ajax({
           url:"/remind_mentor_consent_form",
           method:"POST",
           data:{
               std_id : std_id,
               _token : Laravel.csrfToken,
           },
           success:function(){
              swal({
                    type: 'success',
                    title: 'Reminder Sent',
                    text: 'Reminder has been sent to Mentor',
                    showCloseButton: true,
                })  
           }
        });

    });
});