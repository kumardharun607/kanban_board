$(document).ready(function(){

    $("#loginForm").submit(function(e){

        e.preventDefault();
        //alert("login")

        let formData = {

            email:
            $("#email").val(),

            password:
            $("#password").val()
        };

        $.ajax({

            url:"userlogin_verifyapi.php",

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

                    },4000);
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

                        },7000);
                    }
                }
            }
        });

    });

});
function togglePassword() {

    let passwordField = document.getElementById("password");
    let eyeIcon = document.getElementById("eyeIcon");

    if (passwordField.type === "password") {

        passwordField.type = "text";

        eyeIcon.classList.remove("bi-eye-fill");
        eyeIcon.classList.add("bi-eye-slash-fill");

    } else {

        passwordField.type = "password";

        eyeIcon.classList.remove("bi-eye-slash-fill");
        eyeIcon.classList.add("bi-eye-fill");
    }
}