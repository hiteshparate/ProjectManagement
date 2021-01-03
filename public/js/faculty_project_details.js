$(document).ready(function () {
    $('.req_acc').click(function () {
        var accept_btn = $(this);
        var id = $(this).parent().parent().find(".id").attr('id');
        var event_id = $(this).parent().parent().find(".e_id").attr('id');
        $.ajax({
            url: "/accept_report",
            method: "POST",
            data: {
                id: id,
                event_id: event_id,
                _token: Laravel.csrfToken},
            success: function (data) {
                accept_btn.parent().html("Accepted");
                swal({
                    type: 'success',
                    title: 'Accepted',
                    text: 'Report has been accepted',
                    showCloseButton: true,
                    width: "600px",
                    onOpen: function () {
                        $("#swal2-content").css("font-size", "18px");
                    },
                });
            },
        });
    });

    $('.req_rej').click(function () {
        var reject_btn = $(this);
        var id = $(this).parent().parent().find(".id").attr('id');
        var event_id = $(this).parent().parent().find(".e_id").attr('id');
        $("#swal2-content").css("font-size", "18px");
        swal({
            type: 'error',
            title: 'Wait..',
            text: "If you want this version for further use then please download it first. After rejection you won't be able to download it",
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!',
            width: "600px",
            onOpen: function () {
                $("#swal2-content").css("font-size", "18px");
            }
        }).then(async (result) => {
            console.log(result);
            if (result.value) {
                const {value: reason} = await swal({
                    title: 'Reason For Rejection',
                    input: 'text',
                    inputPlaceholder: 'Enter reason',
                    showCancelButton: true,
                    width: "600px",
                    onOpen: function () {
                        $("#swal2-content").css("font-size", "18px");
                    },
                    inputValidator: (value) => {
                        return !value && 'You need to write something!'
                    }
                });

                if (reason) {
                    $.ajax({
                        url: "/reject_report",
                        method: "POST",
                        data: {
                            id: id,
                            event_id: event_id,
                            reason: reason,
                            _token: Laravel.csrfToken
                        },
                        success: function (data) {
                            reject_btn.parent().html("Rejected");
                            swal({
                                type: 'success',
                                title: 'Rejected',
                                text: 'Report has been rejected',
                                showCloseButton: true,
                                width: "600px",
                                onOpen: function () {
                                    $("#swal2-content").css("font-size", "18px");
                                },
                            });
                            window.location.reload();
                        },
                    });
                }
            } else {
                console.log("KK");
            }
        });
    });

});

    