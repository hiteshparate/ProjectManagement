$(document).ready(function () {
    $(".nominate").on("click", function () {
        var btn = $(this);
        var p_id = btn.parent().parent().find(".p_id").attr("id");
        var se = btn.parent().find(".nominations :selected").val();
        var row = btn.parent().parent();
        var se_name = btn.parent().find(".nominations :selected").text();
        if (se == "n") {
            swal({
                type: 'warning',
                title: 'Error...',
                text: 'Please select faculty for nomination',
                showCloseButton: true,
            });
        } else {
            $.ajax({
                url: '/nominate_se',
                method: 'POST',
                data: {
                    program_id: p_id,
                    sub_exp: se,
                    _token: Laravel.csrfToken,
                },
                success: function () {
                    btn.parent().text(se_name);
                    row.find(".status").text("pending");
                    swal({
                        type: 'success',
                        title: 'Nomination Sent',
                        text: 'Faculty Nomination has been sent',
                        showCloseButton: true,
                    });
                }
            });
        }

    });

    $('.se_acc').on('click', function () {
        var btn = $(this);
        var std_prg_id = btn.parent().parent().find(".stdid").attr("id");
        $.ajax({
            url: "/accept_se_nomination",
            method: "POST",
            data: {
                std_prg_id: std_prg_id,
                _token: Laravel.csrfToken
            },
            success: function (data) {
                btn.parent().html('accepted');
                swal({
                    type: 'success',
                    title: 'Accepted',
                    text: 'You have accepted subject Expert request',
                    showCloseButton: true
                });
            }

        });
    });
    $('.se_rej').on('click', function () {
        var btn = $(this);
        var std_prg_id = btn.parent().parent().find(".stdid").attr("id");
        $.ajax({
            url: "/reject_se_nomination",
            method: "POST",
            data: {
                std_prg_id: std_prg_id,
                _token: Laravel.csrfToken
            },
            success: function (data) {
                btn.parent().html('rejected');
                swal({
                    type: 'success',
                    title: 'Rejected',
                    text: 'You have rejected subject Expert request',
                    showCloseButton: true
                });
            }

        });
    });
    
});