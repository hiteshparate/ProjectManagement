$(document).ready(function () {
    var min_bid = $("#min").val();
    var max_bid = $("#max").val();
    $('.bid_projects').on('click', function () {
        var bid_data = table.rows('.selected').data().toArray();
        var std_id = [];
        for (var i = 0; i < bid_data.length; i++) {
            std_id.push(bid_data[i][1]);
        }
        $.ajax({
            url: "/save_bid_request",
            method: "POST",
            data: {
                std_prg_ids: std_id,
                _token: Laravel.csrfToken
            },
            success: function (data) {
                if (data == "true") {
                    swal({
                        type: 'success',
                        title: 'Bidding done',
                        text: 'Bidding for the selected project is done',
                        showCloseButton: true,
                        onClose: function () {
                            window.location.reload();
                        }
                    });
                } else if (data == "less") {
                    swal({
                        type: 'warning',
                        title: 'Error..',
                        text: 'Please select atleast ' + min_bid + ' projects for bidding',
                        showCloseButton: true,
                    });
                } else if (data == "null") {
                    swal({
                        type: 'warning',
                        title: 'Error..',
                        text: 'Please select projects',
                        showCloseButton: true,
                    });
                } else if (data == "more") {
                    swal({
                        type: 'warning',
                        title: 'Error..',
                        text: 'Please select atmost ' + max_bid + ' projects for bidding',
                        showCloseButton: true,
                    });
                }

            }
        });
    });

    var table = $('#faculty_bid_datatable').DataTable({
        'select': {
            'style': 'multi'
        },

    });
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#faculty_bid_datatable_paginate').addClass("pull-right");
    $('#faculty_bid_datatable_filter').addClass("pull-right");
    $('#faculty_bid_datatable_filter  :input').css("font-size", "15px");
    $('#faculty_bid_datatable_length').find('.form-control').css("font-size", "13px");


//    table.on('select.dt', function () {
//        console.log(table.rows('.selected').data().toArray());
//        var array = [];
//        table.rows('.selected').every(function (rowIdx) {
//            array.push(table.row(rowIdx).data())
//        })
//        console.log(array);
//    })


});