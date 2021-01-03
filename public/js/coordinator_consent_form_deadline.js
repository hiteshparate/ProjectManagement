$(document).ready(function () {
    
    $('#deadline_datatable').DataTable();
    $('#p_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#deadline_datatable_paginate').addClass("pull-right");
    $('#deadline_datatable_filter').addClass("pull-right");
    $('#deadline_datatable_filter  :input').css("font-size", "15px");
    $('#deadline_datatable_length').find('.form-control').css("font-size", "13px");
    
    $('.start_com').on('click', function () {


        var start = $('.s_date').val();
        var end = $('.e_date').val();
        $.ajax({
            url: "/cf_save_start_end_date",
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
                        title: 'Date is set',
                        text: 'Consent Form  date has been set',
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

   

    $('.save_com').on("click", function () {
        var start = $('.s_date').val();
        var end = $('.e_date').val();
        $.ajax({
            url: "/cf_save_start_end_date",
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
                        text: 'Consent Form date has been changed',
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
    
    $('.edit_deadline').on('click',function(){
         $('.save_deadline').removeAttr('hidden');
        $('.edit_deadline').attr('hidden', 'true');
        $('.e_date').removeAttr('disabled');
        $('.s_date').removeAttr('disabled');
        $('.from_deadline_multi').removeAttr('disabled');
        $('.to_deadline_multi').removeAttr('disabled');
    });
    
    
});