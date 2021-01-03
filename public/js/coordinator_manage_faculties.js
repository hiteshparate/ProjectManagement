$(document).ready(function () {
    $('.delete_faculty').on('click', function () {
        var btn = $(this);
        var prg_id = $('.prg_id').attr('id');
        var f_id = $(this).parent().parent().find(".id").attr("id");
        console.log(prg_id);
        $.ajax({
            url: "/co_delete_faculties",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                'prg_id': prg_id,
                'f_id': f_id
            },
            success: function () {
                swal({
                    type: 'success',
                    title: 'Faculty Deleted',
                    text: 'Faculty is deleted from program. You can add faculty from Add Faculties',
                    showCloseButton: true,
                })
                window.location.reload();
            }
        });
    });
});