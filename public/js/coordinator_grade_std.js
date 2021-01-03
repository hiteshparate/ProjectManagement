$(document).ready(function () {
    $('#grading_table tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search" style="width:80px"/>' );
    } );
    
    var table = $('#grading_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                footer: false,
                exportOptions: {
                    columns: [0,1,3,4,5,8,10],
                }
            }
        ]
    });
    $(".buttons-excel").css("font-size","13px");
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#grading_table_paginate').addClass("pull-right");
    $('#grading_table_filter').addClass("pull-right");
    $('#grading_table_filter').css("font-size", "13px");
    $('#grading_table_filter :input').css("font-size", "13px");
    $('#grading_table_length').find('.form-control').css("font-size", "13px");
    
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

    $(".m_comment").on("click", function () {
        $("#model_data").html("");
        var btn = $(this);
        var sub_std_id = $(this).parent().parent().find(".s_id").attr("id");
        $.ajax({
            url: "/get_mentor_comment",
            method: "POST",
            data: {
                sub_std_id: sub_std_id,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                $("#model_data").html(data);
            }
        });
    });

    $(".g_save").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var grade = $(this).parent().find(".grading option:selected").val();

        $.ajax({
            url: "/save_grade_coordinator",
            method: "POST",
            data: {
                s_id: s_id,
                grade: grade,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                if (data == "true") {
                    btn.parent().parent().find(".grading").val(grade);
                    swal({
                        type: 'success',
                        title: 'Saved',
                        text: 'Grading is saved',
                        showCloseButton: true,
                    });
                } else {
                    swal({
                        type: 'warning',
                        title: 'Opps...',
                        text: 'You forget to select grade',
                        showCloseButton: true,
                    });
                }

            }
        });
    });

    $(".g_submit").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var grade = $(this).parent().find(".grading option:selected").val();

        $.ajax({
            url: "/submit_grade_coordinator",
            method: "POST",
            data: {
                s_id: s_id,
                grade: grade,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                if (data == "true") {
                    btn.parent().parent().find(".grading").val(grade);
                    swal({
                        type: 'success',
                        title: 'Submitted',
                        text: 'Grade has been submitted',
                        showCloseButton: true,
                    });
                    btn.parent().text(grade);
                } else {
                    swal({
                        type: 'warning',
                        title: 'Opps...',
                        text: 'You forget to select grade',
                        showCloseButton: true,
                    });
                }

            }
        });
    });



    $(".g_comment").click(function (e) {
        var btn = $(this);

//        console.log(e_id);
        btn.parent().find(".m_c").removeAttr("hidden");
        // btn.text("Submit");
        btn.parent().find(".save_comment").removeAttr("hidden");
        btn.attr("hidden", 'true');
    });

    $(".save_comment").click(function () {
        var btn = $(this);
        var comment = btn.parent().find(".m_c").val();
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
//            console.log(comment);
        $.ajax({
            url: "/coordinator_comment",
            method: "POST",
            data: {
                s_id: s_id,
                comment: comment,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                swal({
                    type: 'success',
                    title: 'Saved',
                    text: 'Comment has been Saved',
                    showCloseButton: true,
                });
                btn.parent().text(comment);

                btn.attr("hidden", "true");

            },
        });
    });

    $(".committee_grade").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var com_fac_id = btn.attr("id");
        $("#committee_data").html("");
        $.ajax({
            url: '/committee_grade',
            method: "POST",
            data: {
                committee_id: com_fac_id,
                subphase_id: s_id,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                $("#committee_data").html(data);
            }
        });
    });

    $(".committee_comment").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var com_fac_id = $(this).parent().parent().find(".com_id").attr("id");
        $("#committee_comment_data").html("");
        $.ajax({
            url: '/committee_comment',
            method: "POST",
            data: {
                committee_id: com_fac_id,
                subphase_id: s_id,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                $("#committee_comment_data").html(data);
            }
        });
    });

    $('.fail_student').on('click', function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var col = btn.parent();
        console.log(s_id);
        swal({
            title: "Are you sure?",
            text: "Student will fail in this subphase.Do you really want to do it?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Do it!",
            closeOnConfirm: false
        },
                function () {
                    $.ajax({
                        url: '/fail_student_subphase',
                        method: "POST",
                        data: {
                            s_id: s_id,
                            _token: Laravel.csrfToken
                        },
                        success: function (data) {
                            console.log(data);
                            col.html("failed");
//                            col.parent().find("select").parent().html("");
//                            col.parent().find(".cm").html("");

                        }
                    });

                    swal("Failed!", "Student has been failed", "success");
                });

    });
    var subphase = $(".subp_name").attr("id");
    var phase = $(".p_name").attr("id");
    $(".final_grade_btn").on("click", function () {
        swal({
            title: "Are you sure, you want to complete the grading for this subphase?",
            text: "Please export current data.After complition, some data might be lost",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, complete grading!",
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "/do_final_grading",
                method: "POST",
                data: {
                    phase: phase,
                    subphase: subphase,
                    _token: Laravel.csrfToken,
                },
                success: function () {
                    swal({
                        title: "Done !!",
                        text: "Grading is done.",
                        type: "success",
                        showCancelButton: true,
                    },
                            function () {
                                window.location.reload();
                            });
                }
            })
        });
    });


});
