$(document).ready(function(){
    
    $('#reports tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    
    var table = $('#reports').DataTable();
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#reports_paginate').addClass("pull-right");
    $('#reports_filter').addClass("pull-right");
    $('#reports_filter  :input').css("font-size","15px");
    $('#reports_length').find('.form-control').css("font-size","13px");
    
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
});