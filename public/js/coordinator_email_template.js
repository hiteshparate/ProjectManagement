$(document).ready(function () {
    $('#summernote').summernote({
        height: 300, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: true,
        popover: {
            image: [],
            link: [],
            air: []
        }
    });
    $('.note-editable').css('font-size', '18px');
    $(".note-toolbar").find(".btn").css("font-size", "15px");
    $(".subject_select").on("change", function () {
        if ($(".subject_select").val() == "n") {
            $("input[name=subject]").removeAttr("disabled");
            $("input[name=subject]").val("");
            $(".note-editable").html("");
        } else {
            $("input[name=subject]").attr("disabled", true);
            $("input[name=subject]").val($(".subject_select option:selected").text());
            var prg_id = $(".prg").attr("id");
            $.ajax({
                url:"/get_mail_template_data",
                method:"post",
                data:{
                    prg_id:prg_id,
                    subject:$(".subject_select").val(),
                    _token:Laravel.csrfToken,
                },
                success:function(data){
                    console.log(data);
                    $(".note-editable").html(data);
                }
            });
        }
    });
    

    $(".save_mail").on("click", function () {
        var tmp_to = $('input[name^=to]').map(function (idx, elem) {
            return $(elem).val();
        }).get();

        var to = tmp_to.toString().split(",");

        var tmp_cc = $('input[name^=cc]').map(function (idx, elem) {
            return $(elem).val();
        }).get();

        var cc = tmp_cc.toString().split(",");

        var subject = $("#sub").val();
//        if (subject == "n") {
//            subject = $("input[name=subject]").val();
//        }
        var email_text = $("#summernote").summernote("code")
                .replace(/<\/p>/gi, "\n")
                .replace(/<br\/?>/gi, "\n")
                .replace(/<\/?[^>]+(>|$)/g, "")
                .replace(/&nbsp;/g," ");
        console.log(email_text);
        var raw_email = $("#summernote").summernote("code");
        $.ajax({
            url:"/send_mail",
            method:"post",
            data:{
              to:to,
              cc:cc,
              subject:subject,
              email:email_text,
              raw_email:raw_email,
              _token:Laravel.csrfToken,
            },
            success:function(){
              swal({
                  type:"success",
                  title:"Mail Sent",
                  text:"Mail has been sent to all selected members"
              });  
            },
        })
    });

});