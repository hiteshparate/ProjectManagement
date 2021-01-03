$(document).ready(function () {
    $(".mail_template").on("change", function () {
        var selected = $(".mail_template option:selected").val();
        if (selected != "n") {
            $.ajax({
                url: "/get_backend_mail_template",
                method: "POST",
                data: {
                    template : selected,
                    _token: Laravel.csrfToken,
                },
                success: function (data) {
                    $(".disp_centent").html(data);
                }
            });
        }else{
            $(".disp_centent").html("");
        }

    });
});