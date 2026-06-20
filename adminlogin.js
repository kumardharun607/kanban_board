$(document).ready(function(){

    $("#loginForm").submit(function(e){

        e.preventDefault();
        //alert("login")

        let formData = {

            email:
            $("#email").val(),

            password:
            $("#admin_password").val()
        };

        $.ajax({

            url:"adminlogin_verifyapi.php",

            type:"POST",

            data:formData,

            dataType:"json",

            success:function(response){

                $("#messageBox")
                .removeClass("hidden");

                if(response.status)
                {
                    //alert("Done status")
                    $("#messageBox")
                    .removeClass("bg-red-500")
                    .addClass("bg-green-500 text-white")
                    .text(response.message);

                    setTimeout(function(){

                        window.location.href =
                        response.location;

                    },3000);
                }
                else
                {
                    $("#messageBox")
                    .removeClass("bg-green-500")
                    .addClass("bg-red-500 text-white")
                    .text(response.message);

                    if(response.redirect)
                    {
                        setTimeout(function(){

                            window.location.href =
                            response.redirect;

                        },3000);
                    }
                }
            }
        });

    });

});
function togglePassword() {

    let password =
        document.getElementById("admin_password");

    let eye =
        document.getElementById("eyeIcon");

    if (password.type === "password") {

        password.type = "text";

        eye.classList.remove("bi-eye-fill");

        eye.classList.add("bi-eye-slash-fill");

    } else {

        password.type = "password";

        eye.classList.remove("bi-eye-slash-fill");

        eye.classList.add("bi-eye-fill");
    }
}