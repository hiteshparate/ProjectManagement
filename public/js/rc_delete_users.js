$(document).ready(function () {
    $('.delete_user').on('click', function () {
        var btn = $(this);
        var col = btn.parent();
        var user_id = btn.parent().parent().find(".del_user").attr("id");
        console.log(user_id);
        swal({
            title: "Are you sure?",
            text: "User  will  be deleted from system.Do you really want to do it?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Do it!",
            closeOnConfirm: false
        },
                function () {
                    $.ajax({
                        url: '/delete_user',
                        method: "POST",
                        data: {
                            user_id: user_id,
                            _token: Laravel.csrfToken
                        },
                        success: function (data) {
                            console.log(data);
                            col.html("deleted");

                        }
                    });

                    swal("Deleted!", "user has been deleted", "success");
                });
        
//        swal({
//            title: "Are you sure?",
//            text: "Student will fail in this subphase.Do you really want to do it?",
//            type: "warning",
//            showCancelButton: true,
//            confirmButtonClass: "btn-danger",
//            confirmButtonText: "Yes, Do it!",
//            closeOnConfirm: false
//        }, function () {
//            $.ajax({
//                url: '/delete_user',
//                method: "POST",
//                data: {
//                    user_id: user_id,
//                    _token: Laravel.csrfToken
//                },
//                success: function () {
//                    
//                    col.html('deleted');
//                }
//            });
//        });
    });
});
