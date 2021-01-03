$(document).ready(function () {
    $('.start_com').on('click', function () {


        var start = $('.s_date').val();
        var end = $('.e_date').val();
        $.ajax({
            url: "/se_save_start_end_date",
            method: 'POST',
            data: {
                start: start,
                end: end,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                if (data == "true") {
                    swal({
                        type: 'success',
                        title: 'Event date is set',
                        text: 'Subject Expert Nomination date has been set',
                        showCloseButton: true,
                    });
                    $('.edit_com').removeAttr('hidden');
                    $('.start_com').attr('hidden', 'true');
                    $('.s_date').attr('disabled', 'true');
                    $('.e_date').attr('disabled', 'true');
                } else {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: data,
                        showCloseButton: true,
                    })
                }
            }
        });

    });

    $('.edit_com').on('click', function () {
        $('.save_com').removeAttr('hidden');
        $('.edit_com').attr('hidden', 'true');
        $('.e_date').removeAttr('disabled');
        $('.s_date').removeAttr('disabled');
    });

    $('.save_com').on("click", function () {
        var start = $('.s_date').val();
        var end = $('.e_date').val();
        $.ajax({
            url: "/se_save_start_end_date",
            method: 'POST',
            data: {
                start: start,
                end: end,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                if (data == "true") {
                    swal({
                        type: 'success',
                        title: 'Date has been changed',
                        text: 'Subject Expert Nomination date has been changed',
                        showCloseButton: true,
                        onClose: function () {
                            window.location.reload();
                        }
                    });
                } else {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: data,
                        showCloseButton: true,
                    })
                }
            }
        });
    });

    $('.bid_start').on('click', function () {
        var start = $('.bid_s_date').val();
        var end = $('.bid_e_date').val();
        var min = $('.bid_min_limit').val();
        var max = $('.bid_max_limit').val();
        $.ajax({
            url: "/start_bid_date",
            method: "POST",
            data: {
                start: start,
                end: end,
                min: min,
                max: max,
                _token: Laravel.csrfToken
            },
            success: function (data) {

                if (data == "true") {
                    swal({
                        type: 'success',
                        title: 'Bidding date and limit is set',
                        text: 'Bidding date and limit have been set ',
                        showCloseButton: true
                    });
                    $('.bid_edit').removeAttr("hidden");
                    $('.bid_s_date').attr("disabled", 'true');
                    $('.bid_e_date').attr("disabled", 'true');
                    $('.bid_max_limit').attr("disabled", 'true');
                    $('.bid_min_limit').attr("disabled", 'true');
                    $('.bid_start').attr("hidden", 'true');

                } else {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: data,
                        showCloseButton: true
                    });
                }

            }
        });
    });

    $('.bid_edit').on('click', function () {
        $('.bid_save').removeAttr('hidden');
        $('.bid_edit').attr('hidden', 'true');
        $('.bid_s_date').removeAttr('disabled');
        $('.bid_e_date').removeAttr('disabled');
        $('.bid_max_limit').removeAttr('disabled');
        $('.bid_min_limit').removeAttr('disabled');
    });

    $('.bid_save').on('click', function () {
        var start = $('.bid_s_date').val();
        var end = $('.bid_e_date').val();
        var min = $('.bid_min_limit').val();
        var max = $('.bid_max_limit').val();
        $.ajax({
            url: "/start_bid_date",
            method: "POST",
            data: {
                start: start,
                end: end,
                min : min,
                max : max,
                _token: Laravel.csrfToken
            },
            success: function (data) {

                if (data == "true") {
                    swal({
                        type: 'success',
                        title: 'Bidding date and limit is changed',
                        text: 'Bidding date and limit have been changed ',
                        showCloseButton: true,
                        onClose: function () {
                            window.location.reload();
                        }
                    });


                } else {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: data,
                        showCloseButton: true
                    });
                }

            }
        });

    });
});