$(document).ready(function () {
    $('#view_aoi_datatable').DataTable();
    $('#view_aoi_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#view_aoi_datatable_paginate').addClass("pull-right");
    $('#view_aoi_datatable_filter').addClass("pull-right");
    $('#view_aoi_datatable_filter  :input').css("font-size", "15px");
    $('#view_aoi_datatable_length').find('.form-control').css("font-size", "13px");
    
    
    $('.delete_aoi').on('click',function(){
       var btn = $(this);
       var id = btn.parent().parent().find(".aoi_id").attr("id");
       $.ajax({
           
            url: "/delete_aoi",
            method: "POST",
            data: {
                id: id,
                _token: Laravel.csrfToken
            },
            success: function (data) {
                console.log("GG");
                btn.parent().html("deleted");
                swal({
                    type: 'success',
                    title: 'Deleted',
                    text: 'Area Of Interest has been Deleted',
                    showCloseButton: true
                    
                   
                });
            
            }
        
       });
       // console.log($id);
    });
    
     var value = 2;
    $('.add_aoi').on('click', function () {
        $('<div class="form-group " ><div class="col-xs-5 " ><p class="font-13 font-bold pull-right m-t-5">Area_of Interest  ' + value +'</p></div><div class="col-xs-6" ><input class="input-sm font-13" name="aoi_'+value+'" type="text"></div>').appendTo(aois);
        $('#values').val(value);
        value++;
        return value;
    });
});
