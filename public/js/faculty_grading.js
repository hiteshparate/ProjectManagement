var IsAjaxExecuting = false;
$(document).ready(function () {
    $('#mentor_datatable').DataTable();
    $('#m_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#mentor_datatable_paginate').addClass("pull-right");
    $('#mentor_datatable_filter').addClass("pull-right");
    $('#mentor_datatable_filter  :input').css("font-size", "15px");
    $('#mentor_datatable_length').find('.form-control').css("font-size", "13px");

    $('#committee_datatable').DataTable();
    $('#c_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#committee_datatable_paginate').addClass("pull-right");
    $('#committee_datatable_filter').addClass("pull-right");
    $('#committee_datatable_filter  :input').css("font-size", "15px");
    $('#committee_datatable_length').find('.form-control').css("font-size", "13px");

    $(".g_save").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var grade = $(this).parent().find(".grading option:selected").val();

        $.ajax({
            url: "/save_grade_mentor",
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
            url: "/submit_grade_mentor",
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
        btn.parent().find(".m_c").removeAttr("hidden");
        btn.parent().find(".save_comment").removeAttr("hidden");
        btn.attr("hidden", 'true');
    });

    $(".save_comment").click(function () {
        var btn = $(this);
        var comment = btn.parent().find(".m_c").val();
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        $.ajax({
            url: "/mentor_comment",
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

    $(".show_comment").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        $.ajax({
            url: "/get_mentor_comment",
            method: "POST",
            data: {
                s_id: s_id,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                $(".view_m_c").val(data);
            }
        });

    });

    $(".com_save").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var com_fac_id = $(this).parent().parent().find(".com_id").attr("id");
        var grade = $(this).parent().find(".grading option:selected").val();

        $.ajax({
            url: "/save_grade_committee",
            method: "POST",
            data: {
                s_id: s_id,
                grade: grade,
                com_fac_id: com_fac_id,
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

    $(".com_submit").on("click", function () {
        var btn = $(this);
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var com_fac_id = $(this).parent().parent().find(".com_id").attr("id");
        var grade = $(this).parent().find(".grading option:selected").val();

        $.ajax({
            url: "/submit_grade_committee",
            method: "POST",
            data: {
                s_id: s_id,
                grade: grade,
                com_fac_id:com_fac_id,
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
    
    $(".com_comment").click(function (e) {
        var btn = $(this);
        btn.parent().find(".m_c").removeAttr("hidden");
        btn.parent().find(".com_save_comment").removeAttr("hidden");
        btn.attr("hidden", 'true');
    });

    $(".com_save_comment").click(function () {
        var btn = $(this);
        var comment = btn.parent().find(".m_c").val();
        var s_id = $(this).parent().parent().find(".s_id").attr("id");
        var com_fac_id = $(this).parent().parent().find(".com_id").attr("id");
        $.ajax({
            url: "/com_comment",
            method: "POST",
            data: {
                s_id: s_id,
                comment: comment,
                com_fac_id:com_fac_id,
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

});